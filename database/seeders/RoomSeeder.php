<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rooms')->insert([
            [
                'room_number' => '101',
                'type' => 'Studio',
                'default_rent' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '201',
                'type' => '2-Bedroom',
                'default_rent' => 850.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}