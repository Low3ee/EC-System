<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoices')->insert([
            [
                'tenant_id' => 1, 
                'amount_due' => 5000.00,
                'amount_paid' => 3500.00,
                'due_date' => '2026-03-05',
                'type' => 'rent',
                'status' => 'partial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 2,
                'amount_due' => 4500.00,
                'amount_paid' => 3500.00,
                'due_date' => '2026-03-15',
                'type' => 'rent',
                'status' => 'partial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1, 
                'amount_due' => 4000.00,
                'amount_paid' => 4000.00,
                'due_date' => '2026-02-05',
                'type' => 'rent',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
