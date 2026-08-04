<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\Gallery;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_details_page_renders(): void
    {
        $property = Property::factory()->create(['title' => 'Beautiful Test Villa']);

        $this->get(route('properties.show', $property->slug))
            ->assertOk()
            ->assertSee('Beautiful Test Villa')
            ->assertSee('Enquire About This Property');
    }

    public function test_viewing_a_property_increments_its_view_count(): void
    {
        $property = Property::factory()->create(['views_count' => 5]);

        $this->get(route('properties.show', $property->slug));

        $this->assertSame(6, $property->fresh()->views_count);
    }

    public function test_viewing_a_property_logs_a_property_view_record(): void
    {
        $property = Property::factory()->create();

        $this->get(route('properties.show', $property->slug));

        $this->assertDatabaseHas('property_views', ['property_id' => $property->id]);
    }

    public function test_property_page_displays_its_amenities(): void
    {
        $property = Property::factory()->create();
        $pool = Amenity::factory()->create(['name' => 'Swimming Pool']);
        $property->amenities()->attach($pool);

        $this->get(route('properties.show', $property->slug))->assertSee('Swimming Pool');
    }

    public function test_property_page_shows_related_properties_from_the_same_category(): void
    {
        $property = Property::factory()->create();
        $related = Property::factory()->count(2)->create(['category_id' => $property->category_id]);
        $unrelated = Property::factory()->create(); // different category, from a different factory call

        $response = $this->get(route('properties.show', $property->slug));

        $response->assertViewHas('relatedProperties', function ($relatedProperties) use ($related) {
            return $relatedProperties->count() === 2
                && $relatedProperties->pluck('id')->diff($related->pluck('id'))->isEmpty();
        });
    }

    public function test_property_details_page_returns_404_for_unknown_slug(): void
    {
        $this->get('/properties/does-not-exist')->assertNotFound();
    }

    public function test_gallery_thumbnail_falls_back_to_full_image_when_no_thumbnail_exists(): void
    {
        $property = Property::factory()->create();
        $gallery = Gallery::factory()->create([
            'property_id' => $property->id,
            'image_path' => 'properties/gallery/full.webp',
            'thumbnail_path' => null,
        ]);

        $this->assertStringContainsString('full.webp', $gallery->thumbnail_url);
    }
}
