<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Apartment', 'icon' => 'bi-building'],
            ['name' => 'Villa', 'icon' => 'bi-house-door'],
            ['name' => 'Independent House', 'icon' => 'bi-house'],
            ['name' => 'Plot & Land', 'icon' => 'bi-map'],
            ['name' => 'Commercial', 'icon' => 'bi-shop'],
            ['name' => 'Penthouse', 'icon' => 'bi-building-fill-check'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'description' => "Browse {$category['name']} listings on RealEstatePro.",
                ]
            );
        }
    }
}
