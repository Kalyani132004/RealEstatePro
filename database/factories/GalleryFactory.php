<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'image_path' => 'properties/gallery/placeholder-' . fake()->numberBetween(1, 9) . '.webp',
            'thumbnail_path' => 'properties/gallery/placeholder-' . fake()->numberBetween(1, 9) . '_thumb.webp',
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
