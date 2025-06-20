<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure there are users
        if (User::count() === 0) {
            \App\Models\User::factory()->count(5)->create();
        }

        // Create posts for existing users
        User::all()->each(function ($user) {
            Post::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
