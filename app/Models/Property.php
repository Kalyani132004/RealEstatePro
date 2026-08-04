<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SOLD = 'sold';
    public const STATUS_RENTED = 'rented';

    public const TYPE_SALE = 'sale';
    public const TYPE_RENT = 'rent';

    protected $fillable = [
        'agent_id',
        'category_id',
        'location_id',
        'title',
        'slug',
        'description',
        'listing_type',
        'status',
        'price',
        'area_sqft',
        'bedrooms',
        'bathrooms',
        'floors',
        'year_built',
        'address',
        'latitude',
        'longitude',
        'virtual_tour_video',
        'floor_plan_image',
        'cover_image',
        'is_featured',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'area_sqft' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'floors' => 'integer',
        ];
    }

    /**
     * Auto-generate a unique slug from the title on create.
     */
    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $base = Str::slug($property->title);
                $slug = $base;
                $counter = 1;

                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$counter}";
                    $counter++;
                }

                $property->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------*/

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('sort_order');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function propertyViews(): HasMany
    {
        return $this->hasMany(PropertyView::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'amenity_property');
    }

    /**
     * Users who have bookmarked this property.
     */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_properties')
            ->withTimestamps();
    }

    /* ---------------------------------------------------------------
     | Scopes — reusable, chainable Query Builder filters
     | Usage (Phase 15): Property::query()->available()->filter($request->all())->paginate();
     |----------------------------------------------------------------*/

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Apply the full advanced-search filter set from the search form
     * (resources/views/properties/search.blade.php, Phase 8) in one call.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['keyword'] ?? null, function (Builder $q, $keyword) {
                // Wrapped in its own group so the OR here never leaks into the
                // other filters chained below (a real bug if left ungrouped —
                // an ungrouped orWhere('address', ...) would OR against the
                // *entire* query, silently ignoring category/location/price/etc).
                $q->where(function (Builder $sub) use ($keyword) {
                    // MySQL's natural-language fulltext mode ignores words
                    // shorter than innodb_ft_min_token_size (default 4 chars),
                    // so short keywords fall back to a plain LIKE scan instead.
                    if (mb_strlen($keyword) >= 4) {
                        $sub->whereFullText(['title', 'description'], $keyword);
                    } else {
                        $sub->where('title', 'like', "%{$keyword}%");
                    }

                    $sub->orWhere('address', 'like', "%{$keyword}%");
                });
            })
            ->when($filters['category'] ?? null, function (Builder $q, $categorySlug) {
                $q->whereHas('category', fn (Builder $c) => $c->where('slug', $categorySlug));
            })
            ->when($filters['location'] ?? null, fn (Builder $q, $locationId) => $q->where('location_id', $locationId))
            ->when($filters['listing_type'] ?? null, fn (Builder $q, $type) => $q->where('listing_type', $type))
            ->when($filters['min_price'] ?? null, fn (Builder $q, $min) => $q->where('price', '>=', $min))
            ->when($filters['max_price'] ?? null, fn (Builder $q, $max) => $q->where('price', '<=', $max))
            ->when($filters['bedrooms'] ?? null, fn (Builder $q, $beds) => $q->where('bedrooms', '>=', $beds))
            ->when($filters['bathrooms'] ?? null, fn (Builder $q, $baths) => $q->where('bathrooms', '>=', $baths))
            ->when($filters['featured'] ?? null, fn (Builder $q) => $q->where('is_featured', true))
            ->when(!empty($filters['amenities']), function (Builder $q) use ($filters) {
                foreach ((array) $filters['amenities'] as $amenityId) {
                    $q->whereHas('amenities', fn (Builder $a) => $a->where('amenities.id', $amenityId));
                }
            })
            ->when($filters['sort'] ?? null, function (Builder $q, $sort) {
                match ($sort) {
                    'price_low' => $q->orderBy('price', 'asc'),
                    'price_high' => $q->orderBy('price', 'desc'),
                    default => $q->latest(),
                };
            }, fn (Builder $q) => $q->latest());
    }

    /* ---------------------------------------------------------------
     | Accessors
     |----------------------------------------------------------------*/

    public function getFormattedPriceAttribute(): string
    {
        $suffix = $this->listing_type === self::TYPE_RENT ? '/mo' : '';

        return '₹' . number_format((float) $this->price) . $suffix;
    }
}
