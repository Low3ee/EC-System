<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'room_number' => '101',
            'type' => 'Single',
            'default_rent' => 5000,
            'capacity' => 1,
            'is_available' => true,
        ]);

        Room::create([
            'room_number' => '102',
            'type' => 'Bedspace',
            'default_rent' => 2500,
            'capacity' => 4,
            'is_available' => true,
        ]);

        Room::create([
            'room_number' => '201',
            'type' => 'Studio',
            'default_rent' => 8000,
            'capacity' => 2,
            'is_available' => true,
        ]);
    }
}