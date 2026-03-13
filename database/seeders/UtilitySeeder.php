<?php

namespace Database\Seeders;

use App\Models\Utility;
use Illuminate\Database\Seeder;

class UtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Utility::create(['name' => 'Electricity']);
        Utility::create(['name' => 'Water']);
        Utility::create(['name' => 'Internet']);
    }
}