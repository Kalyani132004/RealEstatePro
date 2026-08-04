<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Enquiry;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'user_id' => null,
            'agent_id' => Agent::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('9#########'),
            'message' => fake()->paragraph(),
            'status' => Enquiry::STATUS_NEW,
        ];
    }

    /**
     * Ties the enquiry to a specific property AND that property's actual
     * agent — the plain definition() above uses independent factories for
     * property/agent, which is fine for isolated tests but wrong whenever a
     * test cares that "this enquiry belongs to this property's own agent"
     * (e.g. asserting an agent only sees enquiries on their own listings).
     */
    public function forProperty(Property $property): static
    {
        return $this->state(fn (array $attributes) => [
            'property_id' => $property->id,
            'agent_id' => $property->agent_id,
        ]);
    }
}
