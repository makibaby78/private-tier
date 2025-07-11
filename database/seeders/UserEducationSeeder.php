<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserEducation;

class UserEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserEducation::insert([
            [
                'user_id'    => 1,
                'school'     => 'University of Cebu Lapu-lapu Mandaue',
                'degree'     => 'Information of Technology (IT)',
                'level'      => 'college',
                'start_date' => '2019-06-03',
                'end_date'   => '2021-04-30',
                'is_current' => false,
                'visibility' => 'public',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => 1,
                'school'     => 'Mandaue City Comprehensive National High School',
                'degree'     => null,
                'level'      => 'highschool',
                'start_date' => null,
                'end_date'   => null,
                'is_current' => false,
                'visibility' => 'public',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
