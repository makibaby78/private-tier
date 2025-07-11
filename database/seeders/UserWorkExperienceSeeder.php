<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserWorkExperience;

class UserWorkExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserWorkExperience::create([
            'user_id'    => 1,
            'position'   => 'Fullstack Web Developer',
            'company'    => 'EFox Solutions Inc.',
            'location'   => 'Mandaue City',
            'start_date' => '2023-05-29',
            'end_date'   => null,
            'is_current' => true,
            'visibility' => 'public',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
