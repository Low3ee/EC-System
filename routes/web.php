<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
