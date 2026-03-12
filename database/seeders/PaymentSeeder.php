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
                'invoice_id' => 1, 
                'amount' => 3500.00,
                'payment_method' => 'cash',
                'paid_at' => '2026-03-01 10:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'invoice_id' => 2,
                'amount' => 3500.00,
                'payment_method' => 'venmo',
                'paid_at' => '2026-03-02 14:30:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'invoice_id' => 3,
                'amount' => 4000.00,
                'payment_method' => 'bank_transfer',
                'paid_at' => '2026-03-03 09:15:00',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}