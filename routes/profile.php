<?php

use App\Http\Controllers\My\ClientController;
use App\Http\Controllers\My\ClientDebtController;
use App\Http\Controllers\My\ClientDebtPaymentController;
use App\Http\Controllers\My\OrderController;
use App\Http\Controllers\My\OrderDebtController;
use Illuminate\Support\Facades\Route;

// Order routes
Route::resource('orders', OrderController::class);
Route::get('/orders/{order}/debts', [OrderDebtController::class, 'create'])->name('order-debts.create');
Route::post('/orders/{order}/debts', [OrderDebtController::class, 'store'])->name('order-debts.store');

// Client routes
Route::resource('clients', ClientController::class);
Route::get('client-debts', [ClientDebtController::class, 'index'])->name('client-debts.index');
Route::post('client-debts/{client_debt}/client-debt-payments', [ClientDebtPaymentController::class, 'store'])->name('client-debt-payment.store');

