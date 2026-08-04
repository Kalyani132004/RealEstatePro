<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->agent(),
            'agency_name' => fake()->company(),
            'license_no' => strtoupper(fake()->bothify('LIC-####??')),
            'bio' => fake()->paragraph(),
            'whatsapp' => fake()->numerify('9#########'),
            'experience_years' => fake()->numberBetween(1, 20),
            'rating' => fake()->randomFloat(1, 3, 5),
            'is_verified' => fake()->boolean(70),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => ['is_verified' => true]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['is_verified' => false]);
    }
}
