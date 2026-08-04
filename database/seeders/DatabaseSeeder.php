<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: reference data (categories/locations/amenities) must
     * exist before any Property records are created (Phase 19 factories).
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            AmenitySeeder::class,
        ]);
    }
}
