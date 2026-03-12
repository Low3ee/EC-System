<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'room_number' => '101',
                'type' => 'Studio',
                'default_rent' => 500.00,
            ],
            [
                'room_number' => '201',
                'type' => '2-Bedroom',
                'default_rent' => 850.00,
            ],
            [
                'room_number' => '301',
                'type' => 'Solo Room',
                'default_rent' => 400.00,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}