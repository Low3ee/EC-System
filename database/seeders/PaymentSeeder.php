<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'tenant_id' => 1,
                'amount' => 3500,
                'payment_date' => '2026-03-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 2,
                'amount' => 3500,
                'payment_date' => '2026-03-02',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tenant_id' => 3,
                'amount' => 4000,
                'payment_date' => '2026-03-03',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}