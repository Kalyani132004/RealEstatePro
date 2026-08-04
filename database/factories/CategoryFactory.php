<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Apartment', 'Villa', 'Independent House', 'Plot & Land', 'Commercial', 'Penthouse', 'Studio', 'Farmhouse',
        ]) . ' ' . fake()->unique()->numerify('##');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => 'bi-house-door',
            'description' => fake()->sentence(),
        ];
    }
}
