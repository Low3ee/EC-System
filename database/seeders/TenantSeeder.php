<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'phone' => '09123456789',
                'room_id' => 1,
                'base_rent' => 5000.00,
                'due_day' => 5,
                'lease_start' => now()->subMonths(2),
                'is_active' => true,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'phone' => '09987654321',
                'room_id' => 2,
                'base_rent' => 4500.00,
                'due_day' => 15,
                'lease_start' => now()->subMonth(),
                'is_active' => true,
            ],
        ];

        foreach ($tenants as $tenantData) {
            Tenant::create($tenantData);
        }
    }
}