<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    /** @use HasFactory<\Database\Factories\AgentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_name',
        'license_no',
        'bio',
        'whatsapp',
        'experience_years',
        'rating',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'rating' => 'decimal:1',
            'experience_years' => 'integer',
        ];
    }

    /* ---------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    /* ---------------------------------------------------------------
     | Scopes
     |----------------------------------------------------------------*/

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
