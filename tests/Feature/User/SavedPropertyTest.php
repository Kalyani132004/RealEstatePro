<?php

namespace Tests\Feature\User;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedPropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_toggle_saved_property(): void
    {
        $property = Property::factory()->create();

        $this->postJson(route('saved-properties.toggle'), ['property_id' => $property->id])
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_save_a_property(): void
    {
        $user = $this->actingAsUser();
        $property = Property::factory()->create();

        $response = $this->postJson(route('saved-properties.toggle'), ['property_id' => $property->id]);

        $response->assertOk()->assertJson(['saved' => true]);
        $this->assertTrue($user->savedProperties()->where('property_id', $property->id)->exists());
    }

    public function test_toggling_an_already_saved_property_removes_it(): void
    {
        $user = $this->actingAsUser();
        $property = Property::factory()->create();
        $user->savedProperties()->attach($property->id);

        $response = $this->postJson(route('saved-properties.toggle'), ['property_id' => $property->id]);

        $response->assertOk()->assertJson(['saved' => false]);
        $this->assertFalse($user->savedProperties()->where('property_id', $property->id)->exists());
    }

    public function test_saved_properties_index_shows_only_the_users_own_saved_list(): void
    {
        $user = $this->actingAsUser();
        $mySaved = Property::factory()->create();
        $notMine = Property::factory()->create();

        $user->savedProperties()->attach($mySaved->id);

        $response = $this->get(route('user.saved-properties'));

        $response->assertOk();
        $response->assertViewHas('savedProperties', function ($saved) use ($mySaved, $notMine) {
            return $saved->contains('id', $mySaved->id) && ! $saved->contains('id', $notMine->id);
        });
    }
}
