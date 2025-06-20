<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('friendships')->insertOrIgnore([
            [
                'user_id'    => 1,
                'friend_id'  => 2,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id'    => 2,
                'friend_id'  => 1,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id'    => 1,
                'friend_id'  => 3,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id'    => 1,
                'friend_id'  => 5,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 6,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 7,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 8,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 9,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 10,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
                        [
                'user_id'    => 1,
                'friend_id'  => 4,
                'status'     => 'accepted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
