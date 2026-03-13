<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('room_utility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('utility_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['room_id', 'utility_id']);
        });

        // Seed default utilities
        DB::table('utilities')->insert([
            ['name' => 'Electricity', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Water', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Garbage Collection', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_utility');
        Schema::dropIfExists('utilities');
    }
};