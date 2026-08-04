<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->numberBetween(1, 5) . ' BHK ' . fake()->randomElement(['Apartment', 'Villa', 'House']) . ' in ' . fake()->streetName();

        return [
            'agent_id' => Agent::factory(),
            'category_id' => Category::factory(),
            'location_id' => Location::factory(),
            'title' => $title,
            'description' => fake()->paragraphs(3, true),
            'listing_type' => fake()->randomElement([Property::TYPE_SALE, Property::TYPE_RENT]),
            'status' => Property::STATUS_AVAILABLE,
            'price' => fake()->numberBetween(500000, 20000000),
            'area_sqft' => fake()->numberBetween(500, 5000),
            'bedrooms' => fake()->numberBetween(1, 5),
            'bathrooms' => fake()->numberBetween(1, 4),
            'floors' => fake()->numberBetween(1, 3),
            'year_built' => fake()->numberBetween(1990, 2025),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(8, 34),
            'longitude' => fake()->longitude(68, 97),
            'cover_image' => 'properties/covers/placeholder.webp',
            'is_featured' => fake()->boolean(20),
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }

    public function sale(): static
    {
        return $this->state(fn (array $attributes) => ['listing_type' => Property::TYPE_SALE]);
    }

    public function rent(): static
    {
        return $this->state(fn (array $attributes) => ['listing_type' => Property::TYPE_RENT]);
    }

    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => ['status' => $status]);
    }
}
