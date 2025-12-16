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
            'firstname' => 'Albert',
            'lastname' => 'Pogado',
            'username' => 'albert',
            'email' => 'albert@gmail.com',
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
            'birthdate' => now(),
        ]);

        User::factory()->create([
            'firstname' => 'Alfie',
            'lastname' => 'Pogado',
            'username' => 'alfie',
            'email' => 'alfie@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Myla Mae',
            'lastname' => 'Amistad',
            'username' => 'mylamae',
            'email' => 'mylamae@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Edrich',
            'lastname' => 'Enriquez',
            'username' => 'edrich',
            'email' => 'edrich@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Togonon',
            'username' => 'john',
            'email' => 'john@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Paul Micheal',
            'lastname' => 'Rivas',
            'username' => 'paulmicheal',
            'email' => 'paulmicheal@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'firstname' => 'Potcholo',
            'lastname' => 'Galenzoga',
            'username' => 'potcholo',
            'email' => 'potcholo@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        $this->call([
            FriendshipSeeder::class,
            PostSeeder::class,
            UserWorkExperienceSeeder::class,
            UserEducationSeeder::class,
            UserPlaceSeeder::class,
            UserContactSeeder::class,
            UserRelationshipSeeder::class,
            CountrySeeder::class,
        ]);
    }
}
