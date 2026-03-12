<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
    $table->decimal('amount_due', 10, 2);
    $table->decimal('amount_paid', 10, 2)->default(0);
    $table->date('due_date');
    $table->enum('type', ['rent', 'utility', 'deposit', 'fine'])->default('rent');
    $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
