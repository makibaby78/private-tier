<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'firstname' => 'Max Loued',
            'lastname' => 'Baisac',
            'username' => 'maxloued',
            'email' => 'max.baisac@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Stephanie',
            'lastname' => 'Montera',
            'username' => 'stephanie',
            'email' => 'stephanie@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Christian',
            'lastname' => 'Mahusay',
            'username' => 'christian',
            'email' => 'christian@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Jaymar',
            'lastname' => 'Cena',
            'username' => 'jaymar',
            'email' => 'jaymar@gmail.com',
            'password' => Hash::make('123456'),
        ]);


    }
}
