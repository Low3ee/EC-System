<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Landlord Admin',
            'email' => 'admin@ecsystem.com',
            'password' => '$2y$12$aYIAjYhjeb.TOPmcT40QU.e8401SNIGpNI3V8BQre7iWNRCHrvdx2',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}