<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoomUtilityController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('rooms', RoomController::class);

    // Room Utilities
    Route::post('/rooms/{room}/utilities', [RoomUtilityController::class, 'store'])->name('rooms.utilities.store');
    Route::delete('/rooms/{room}/utilities/{utility}', [RoomUtilityController::class, 'destroy'])->name('rooms.utilities.destroy');

    Route::resource('tenants', TenantController::class);

    Route::post('/invoices/generate', [InvoiceController::class, 'generateMonthlyInvoices'])->name('invoices.generate');
    Route::resource('invoices', InvoiceController::class);

    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
});

require __DIR__.'/auth.php';