<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserContact;


class UserContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserContact::insert([
            [
                'user_id'    => 1,
                'type'       => 'phone',
                'value'      => '+639453529874',
                'label'      => 'Mobile',
                'visibility' => 'public',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => 1,
                'type'       => 'email',
                'value'      => 'max.baisac@gmail.com',
                'label'      => 'Work',
                'visibility' => 'friends',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
