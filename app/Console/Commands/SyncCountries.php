<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\City;

class SyncCountries extends Command
{
    protected $signature = 'sync:countries';
    protected $description = 'Sync countries (and cities optionally) from CountriesNow API';

    public function handle(): int
    {
        $this->info('Fetching countries from countriesnow.space API...');

        $response = Http::timeout(30)->get('https://countriesnow.space/api/v0.1/countries');

        if ($response->failed()) {
            $this->error('Failed to fetch countries: HTTP ' . $response->status());
            return self::FAILURE;
        }

        $json = $response->json();
        if (!isset($json['data']) || !is_array($json['data'])) {
            $this->error('Invalid API response.');
            return self::FAILURE;
        }

        $countries = $json['data'];
        $syncedCountries = 0;
        $syncedCities = 0;

        foreach ($countries as $item) {
            if (empty($item['country'])) continue;

            $code = $item['iso2'] ?? null;

            if (!$code) {
                // skip or generate a temporary unique code
                $this->warn("Skipping country {$item['country']} because code is missing.");
                continue;
            }

            $country = Country::updateOrCreate(
                ['code' => $item['iso2'] ?? null],
                [
                    'name'      => $item['country'],
                    'iso3'      => $item['iso3'] ?? null,
                    'is_active' => true,
                ]
            );

            $syncedCountries++;

            // Optional: save cities
            if (!empty($item['cities']) && is_array($item['cities'])) {
                foreach ($item['cities'] as $cityName) {
                    City::updateOrCreate(
                        ['country_id' => $country->id, 'name' => $cityName],
                        ['is_active' => true]
                    );
                    $syncedCities++;
                }
            }
        }

        $this->info("Successfully synced {$syncedCountries} countries.");
        $this->info("Successfully synced {$syncedCities} cities.");

        return self::SUCCESS;
    }
}
