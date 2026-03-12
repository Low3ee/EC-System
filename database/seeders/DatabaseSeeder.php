<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    $this->call([
        UserSeeder::class,    // The Admin
        RoomSeeder::class,    // The Building
        TenantSeeder::class,  // The People
        InvoiceSeeder::class, // The Bills (REQUIRED FOR PAYMENTS)
        PaymentSeeder::class, // The Cash
    ]);
}
}
