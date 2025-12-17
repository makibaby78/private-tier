<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/countries.json'));
        $countries = json_decode($json, true);

        foreach ($countries as $item) {
            if (empty($item['alpha_2'])) continue;

            Country::updateOrCreate(
                ['code' => $item['alpha_2']],
                [
                    'name'       => $item['name'] ?? null,
                    'iso3'       => $item['alpha_3'] ?? null,
                    'region'     => $item['continent'] ?? null,
                    'phone_code' => $item['phone'] ?? null,
                    'is_active'  => true,
                ]
            );
        }

        City::insert([
            [
                'country_id' => 178,
                'name' => 'Consolacion, Cebu',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 3,
                'name' => 'Herat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);        

    }
}
