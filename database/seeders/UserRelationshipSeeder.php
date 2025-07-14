<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRelationship;

class UserRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRelationship::create([
            'user_id' => 3,
            'status' => 'in_a_relationship',
            'partner_id' => 7,
            'since' => '2023-02-14',
            'visibility' => 'public',
        ]);
    }
}
