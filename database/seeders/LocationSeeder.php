<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['city' => 'Mumbai', 'state' => 'Maharashtra', 'latitude' => 19.0760, 'longitude' => 72.8777],
            ['city' => 'Pune', 'state' => 'Maharashtra', 'latitude' => 18.5204, 'longitude' => 73.8567],
            ['city' => 'Nashik', 'state' => 'Maharashtra', 'latitude' => 19.9975, 'longitude' => 73.7898],
            ['city' => 'Bengaluru', 'state' => 'Karnataka', 'latitude' => 12.9716, 'longitude' => 77.5946],
            ['city' => 'Hyderabad', 'state' => 'Telangana', 'latitude' => 17.3850, 'longitude' => 78.4867],
            ['city' => 'Delhi', 'state' => 'Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
            ['city' => 'Ahmedabad', 'state' => 'Gujarat', 'latitude' => 23.0225, 'longitude' => 72.5714],
            ['city' => 'Chennai', 'state' => 'Tamil Nadu', 'latitude' => 13.0827, 'longitude' => 80.2707],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['city' => $location['city'], 'state' => $location['state']],
                [
                    'country' => 'India',
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                ]
            );
        }
    }
}
