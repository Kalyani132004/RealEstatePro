<?php

namespace Tests\Feature\Agent;

use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_agent_can_view_their_own_properties_index(): void
    {
        $agent = $this->actingAsAgent();
        Property::factory()->count(2)->create(['agent_id' => $agent->agent->id]);

        $this->get(route('agent.properties.index'))->assertOk();
    }

    public function test_agent_can_create_a_property(): void
    {
        $agent = $this->actingAsAgent();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $response = $this->post(route('agent.properties.store'), [
            'title' => 'New Test Listing',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'listing_type' => 'sale',
            'status' => 'available',
            'description' => 'A wonderful, spacious property with plenty of natural light.',
            'price' => 2500000,
            'area_sqft' => 1200,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'floors' => 1,
            'address' => '123 Test Street',
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 1200, 800),
        ]);

        $response->assertRedirect(route('agent.properties.index'));

        $this->assertDatabaseHas('properties', [
            'title' => 'New Test Listing',
            'agent_id' => $agent->agent->id,
        ]);
    }

    public function test_creating_a_property_requires_a_cover_image(): void
    {
        $this->actingAsAgent();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $this->post(route('agent.properties.store'), [
            'title' => 'Missing Cover Image',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'listing_type' => 'sale',
            'status' => 'available',
            'description' => 'Description long enough to pass validation checks.',
            'price' => 1000000,
            'area_sqft' => 900,
            'address' => '1 Test Ave',
        ])->assertSessionHasErrors('cover_image');
    }

    public function test_agent_can_update_their_own_property(): void
    {
        $agent = $this->actingAsAgent();
        $property = Property::factory()->create(['agent_id' => $agent->agent->id, 'title' => 'Old Title']);

        $this->put(route('agent.properties.update', $property->id), [
            'title' => 'Updated Title',
            'category_id' => $property->category_id,
            'location_id' => $property->location_id,
            'listing_type' => $property->listing_type,
            'status' => 'available',
            'description' => $property->description,
            'price' => $property->price,
            'area_sqft' => $property->area_sqft,
            'address' => $property->address,
        ])->assertRedirect(route('agent.properties.index'));

        $this->assertSame('Updated Title', $property->fresh()->title);
    }

    public function test_agent_cannot_update_another_agents_property(): void
    {
        $this->actingAsAgent();
        $otherAgent = \App\Models\Agent::factory()->create();
        $property = Property::factory()->create(['agent_id' => $otherAgent->id]);

        $this->put(route('agent.properties.update', $property->id), [
            'title' => 'Hijacked Title',
        ])->assertForbidden();
    }

    public function test_agent_cannot_delete_another_agents_property(): void
    {
        $this->actingAsAgent();
        $otherAgent = \App\Models\Agent::factory()->create();
        $property = Property::factory()->create(['agent_id' => $otherAgent->id]);

        $this->delete(route('agent.properties.destroy', $property->id))->assertForbidden();
        $this->assertNotSoftDeleted('properties', ['id' => $property->id]);
    }

    public function test_agent_can_soft_delete_their_own_property(): void
    {
        $agent = $this->actingAsAgent();
        $property = Property::factory()->create(['agent_id' => $agent->agent->id]);

        $this->delete(route('agent.properties.destroy', $property->id))
            ->assertRedirect();

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
    }

    public function test_a_regular_user_cannot_access_agent_routes(): void
    {
        $this->actingAsUser();

        $this->get(route('agent.dashboard'))->assertForbidden();
    }

    public function test_guests_are_redirected_to_login_for_agent_routes(): void
    {
        $this->get(route('agent.dashboard'))->assertRedirect(route('login'));
    }
}
