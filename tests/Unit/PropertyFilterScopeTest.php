<?php

namespace Tests\Unit;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyFilterScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_scope_excludes_sold_and_rented_properties(): void
    {
        Property::factory()->count(2)->create(['status' => Property::STATUS_AVAILABLE]);
        Property::factory()->create(['status' => Property::STATUS_SOLD]);
        Property::factory()->create(['status' => Property::STATUS_RENTED]);

        $this->assertSame(2, Property::available()->count());
    }

    public function test_featured_scope_only_returns_featured_properties(): void
    {
        Property::factory()->featured()->count(2)->create();
        Property::factory()->create(['is_featured' => false]);

        $this->assertSame(2, Property::featured()->count());
    }

    public function test_filter_scope_with_no_filters_returns_everything(): void
    {
        Property::factory()->count(3)->create();

        $this->assertSame(3, Property::query()->filter([])->count());
    }

    public function test_filter_scope_combines_multiple_conditions_with_and_logic(): void
    {
        $match = Property::factory()->sale()->create(['price' => 3000000, 'bedrooms' => 3]);
        Property::factory()->rent()->create(['price' => 3000000, 'bedrooms' => 3]); // wrong listing_type
        Property::factory()->sale()->create(['price' => 9000000, 'bedrooms' => 3]); // wrong price

        $results = Property::query()->filter([
            'listing_type' => 'sale',
            'max_price' => 5000000,
            'bedrooms' => 2,
        ])->get();

        $this->assertCount(1, $results);
        $this->assertSame($match->id, $results->first()->id);
    }

    public function test_slug_is_auto_generated_from_title_on_create(): void
    {
        $property = Property::factory()->create(['title' => 'Sea View Apartment', 'slug' => null]);

        $this->assertSame('sea-view-apartment', $property->slug);
    }

    public function test_duplicate_titles_get_a_unique_incrementing_slug(): void
    {
        $first = Property::factory()->create(['title' => 'Green Valley Home', 'slug' => null]);
        $second = Property::factory()->create(['title' => 'Green Valley Home', 'slug' => null]);

        $this->assertSame('green-valley-home', $first->slug);
        $this->assertSame('green-valley-home-1', $second->slug);
    }

    public function test_formatted_price_accessor_appends_per_month_suffix_for_rentals(): void
    {
        $rental = Property::factory()->rent()->create(['price' => 25000]);
        $sale = Property::factory()->sale()->create(['price' => 2500000]);

        $this->assertSame('₹25,000/mo', $rental->formatted_price);
        $this->assertSame('₹2,500,000', $sale->formatted_price);
    }
}
