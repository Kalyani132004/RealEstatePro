<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Amenity>
 */
class AmenityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Swimming Pool', 'Gym', 'Parking', 'Power Backup', 'Security', 'Clubhouse',
                'Garden', 'Elevator', 'Kids Play Area', 'Fire Safety', 'Water Supply', 'Wi-Fi',
            ]),
            'icon' => 'bi-check2-circle',
        ];
    }
}
