<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPlace;

class UserPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPlace::create([
            'user_id' => 1,
            'type' => 'current_city',
            'city_id' => 1,
            'region' => 'Central Visayas',
            'country_id' => 178,
            'visibility' => 'public',
        ]);
    }
}
