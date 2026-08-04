<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_path',
        'thumbnail_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Falls back to the full-size image if no thumbnail was generated
     * (e.g. galleries created before Phase 16's thumbnail pipeline existed).
     */
    public function getThumbnailUrlAttribute(): string
    {
        $path = $this->thumbnail_path ?: $this->image_path;

        return asset('storage/' . $path);
    }
}
