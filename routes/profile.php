<?php

use App\Http\Controllers\My\ClientController;
use App\Http\Controllers\My\ClientDebtController;
use App\Http\Controllers\My\ClientDebtPaymentController;
use App\Http\Controllers\My\NomenclatureOperationController;
use App\Http\Controllers\My\OrderController;
use App\Http\Controllers\My\OrderDebtController;
use Illuminate\Support\Facades\Route;

// Order routes
Route::resource('orders', OrderController::class);
Route::post('/orders/{order}/mark-as-send', [OrderController::class, 'markAsSend'])->name('orders.mark-as-send');
Route::post('/orders/{order}/mark-as-cancel', [OrderController::class, 'markAsCancel'])->name('orders.mark-as-cancel');
Route::get('/orders/{order}/debts', [OrderDebtController::class, 'create'])->name('order-debts.create');
Route::post('/orders/{order}/debts', [OrderDebtController::class, 'store'])->name('order-debts.store');

// Client routes
Route::resource('clients', ClientController::class);
Route::get('client-debts', [ClientDebtController::class, 'index'])->name('client-debts.index');
Route::post('clients/{client}/client-debts/{client_debt}/client-debt-payments', [ClientDebtPaymentController::class, 'store'])->name('client-debt-payment.store');

Route::post('nomenclatures-operations/refund-order', [NomenclatureOperationController::class, 'refundOrder'])->name('nomenclature-operations.order-refund');
