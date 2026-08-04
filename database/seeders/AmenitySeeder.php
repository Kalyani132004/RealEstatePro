<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Swimming Pool', 'icon' => 'bi-water'],
            ['name' => 'Gymnasium', 'icon' => 'bi-bicycle'],
            ['name' => 'Covered Parking', 'icon' => 'bi-p-square'],
            ['name' => 'Power Backup', 'icon' => 'bi-lightning-charge'],
            ['name' => '24/7 Security', 'icon' => 'bi-shield-check'],
            ['name' => 'Clubhouse', 'icon' => 'bi-people'],
            ['name' => 'Children\'s Play Area', 'icon' => 'bi-emoji-smile'],
            ['name' => 'Lift / Elevator', 'icon' => 'bi-arrow-up-square'],
            ['name' => 'Garden', 'icon' => 'bi-tree'],
            ['name' => 'Pet Friendly', 'icon' => 'bi-heart' ],
            ['name' => 'Wi-Fi Ready', 'icon' => 'bi-wifi'],
            ['name' => 'Air Conditioning', 'icon' => 'bi-snow'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon']]
            );
        }
    }
}
