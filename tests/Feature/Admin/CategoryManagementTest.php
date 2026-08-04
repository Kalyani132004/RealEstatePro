<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_categories(): void
    {
        $this->actingAsUser();

        $this->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_admin_can_view_categories_index(): void
    {
        $this->actingAsAdmin();
        Category::factory()->count(3)->create();

        $this->get(route('admin.categories.index'))->assertOk();
    }

    public function test_admin_can_create_a_category(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.categories.store'), [
            'name' => 'Farmhouse',
            'icon' => 'bi-tree',
            'description' => 'Spacious countryside properties.',
        ])->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Farmhouse', 'slug' => 'farmhouse']);
    }

    public function test_category_name_must_be_unique(): void
    {
        $this->actingAsAdmin();
        Category::factory()->create(['name' => 'Villa']);

        $this->post(route('admin.categories.store'), ['name' => 'Villa'])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_a_category(): void
    {
        $this->actingAsAdmin();
        $category = Category::factory()->create(['name' => 'Old Name']);

        $this->put(route('admin.categories.update', $category->id), [
            'name' => 'New Name',
            'icon' => 'bi-house',
        ])->assertRedirect();

        $this->assertSame('New Name', $category->fresh()->name);
        $this->assertSame('new-name', $category->fresh()->slug);
    }

    public function test_admin_can_delete_a_category(): void
    {
        $this->actingAsAdmin();
        $category = Category::factory()->create();

        $this->delete(route('admin.categories.destroy', $category->id))->assertRedirect();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
