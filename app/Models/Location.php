<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'country',
        'zip_code',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * "City, State" — used across dropdowns and cards.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->city}, {$this->state}";
    }
}
