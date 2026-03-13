<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room101 = Room::where('room_number', '101')->first();
        $room102 = Room::where('room_number', '102')->first();

        // Active tenant in a single room
        Tenant::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'room_id' => $room101->id,
            'base_rent' => 5000,
            'status' => 'Active', // Use new status column
            'lease_start' => now()->subMonths(3),
        ]);

        // Two active tenants in a bedspace room
        Tenant::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'room_id' => $room102->id,
            'base_rent' => 2500,
            'status' => 'Active',
            'lease_start' => now()->subMonths(6),
        ]);

        // A tenant who has moved out
        Tenant::create([
            'name' => 'Old Tenant',
            'email' => 'old.tenant@example.com',
            'phone' => '5555555555',
            'room_id' => null, // room_id is now nullable
            'base_rent' => 2500,
            'status' => 'Moved Out', // Set status for moved out tenant
            'lease_start' => now()->subYear(),
        ]);
    }
}