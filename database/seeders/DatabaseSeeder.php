<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Utility;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UtilitySeeder::class,
            RoomSeeder::class,
            TenantSeeder::class,
        ]);

        // Attach utilities to rooms
        $room101 = Room::where('room_number', '101')->first();
        $room102 = Room::where('room_number', '102')->first();
        $water = Utility::where('name', 'Water')->first();
        $electricity = Utility::where('name', 'Electricity')->first();

        $room101->utilities()->attach($water->id, [
            'amount' => 300,
            'description' => 'Fixed monthly water fee'
        ]);
        $room101->utilities()->attach($electricity->id, [
            'amount' => 800,
            'description' => 'Billed via sub-meter' 
        ]);

        $room102->utilities()->attach($water->id, ['amount' => 500]); 

        // Update room availability based on tenant count
        $rooms = Room::withCount(['tenants' => function ($query) {
            $query->where('status', 'Active');
        }])->get();
        foreach ($rooms as $room) {
            $room->update(['is_available' => $room->tenants_count < $room->capacity]);
        }
    }
}