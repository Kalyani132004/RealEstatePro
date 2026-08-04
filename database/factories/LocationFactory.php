<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'city' => fake()->unique()->city(),
            'state' => fake()->state(),
            'country' => 'India',
            'zip_code' => fake()->postcode(),
            'latitude' => fake()->latitude(8, 34),
            'longitude' => fake()->longitude(68, 97),
        ];
    }
}
