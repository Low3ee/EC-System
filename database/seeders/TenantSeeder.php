<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tenants')->insert([
            [
                'name' => 'Juan Dela Cruz',
                'phone' => '09123456789',
                'room_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Maria Santos',
                'phone' => '09987654321',
                'room_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pedro Reyes',
                'phone' => '09112223344',
                'room_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}