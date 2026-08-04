<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_renders_available_properties(): void
    {
        Property::factory()->count(3)->create(['status' => Property::STATUS_AVAILABLE]);
        Property::factory()->create(['status' => Property::STATUS_SOLD]);

        $response = $this->get(route('properties.search'));

        $response->assertOk();
        $response->assertViewHas('properties', function ($properties) {
            return $properties->total() === 3;
        });
    }

    public function test_can_filter_by_category(): void
    {
        $apartments = Category::factory()->create(['name' => 'Apartment']);
        $villas = Category::factory()->create(['name' => 'Villa']);

        Property::factory()->count(2)->create(['category_id' => $apartments->id]);
        Property::factory()->create(['category_id' => $villas->id]);

        $response = $this->get(route('properties.search', ['category' => $apartments->slug]));

        $response->assertViewHas('properties', fn ($properties) => $properties->total() === 2);
    }

    public function test_can_filter_by_price_range(): void
    {
        Property::factory()->create(['price' => 1000000]);
        Property::factory()->create(['price' => 5000000]);
        Property::factory()->create(['price' => 9000000]);

        $response = $this->get(route('properties.search', ['min_price' => 2000000, 'max_price' => 6000000]));

        $response->assertViewHas('properties', fn ($properties) => $properties->total() === 1);
    }

    public function test_can_filter_by_listing_type(): void
    {
        Property::factory()->sale()->count(2)->create();
        Property::factory()->rent()->count(3)->create();

        $response = $this->get(route('properties.search', ['listing_type' => 'rent']));

        $response->assertViewHas('properties', fn ($properties) => $properties->total() === 3);
    }

    public function test_keyword_search_matches_title(): void
    {
        Property::factory()->create(['title' => 'Sunrise Villa with Ocean View']);
        Property::factory()->count(4)->create(['title' => 'Downtown Studio Apartment']);

        $response = $this->get(route('properties.search', ['keyword' => 'Sunrise']));

        $response->assertViewHas('properties', fn ($properties) => $properties->total() === 1);
    }

    /**
     * Regression test for the bug fixed in Phase 15: the keyword search's
     * orWhere('address', ...) was previously ungrouped, which meant it
     * silently OR'd against the ENTIRE query — a keyword match in the
     * address would leak in properties that don't match other active
     * filters (e.g. category) at all. This must stay grouped.
     */
    public function test_keyword_filter_does_not_leak_across_other_filters(): void
    {
        $apartments = Category::factory()->create();
        $villas = Category::factory()->create();

        // This property's address contains "Main Street" but is the WRONG category.
        Property::factory()->create([
            'category_id' => $villas->id,
            'address' => '123 Main Street',
            'title' => 'Villa Not Matching Category Filter',
        ]);

        // This one matches both the category AND could match "Main Street" in keyword.
        Property::factory()->create([
            'category_id' => $apartments->id,
            'address' => '456 Main Street',
            'title' => 'Apartment Matching Category Filter',
        ]);

        // Padding rows so "Main Street" doesn't hit MySQL's fulltext 50% stopword
        // threshold (it's matched via the plain LIKE branch here since the
        // keyword is on `address`, not `title`/`description`, but padding keeps
        // the dataset realistic regardless).
        Property::factory()->count(3)->create(['address' => '789 Another Road']);

        $response = $this->get(route('properties.search', [
            'category' => $apartments->slug,
            'keyword' => 'Main Street',
        ]));

        // Only the apartment should match — the villa must NOT leak through
        // just because its address also contains the keyword.
        $response->assertViewHas('properties', fn ($properties) => $properties->total() === 1);
    }

    public function test_ajax_request_returns_partial_view_only(): void
    {
        Property::factory()->count(2)->create();

        $response = $this->get(route('properties.search'), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertViewIs('properties.partials._results');
    }
}
