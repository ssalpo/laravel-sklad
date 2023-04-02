<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientDebtController;
use App\Http\Controllers\Admin\ClientDiscountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MixtureCompositionController;
use App\Http\Controllers\Admin\MixtureCompositionItemController;
use App\Http\Controllers\Admin\NomenclatureArrivalController;
use App\Http\Controllers\Admin\NomenclatureController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StorehouseController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('storehouses', [StorehouseController::class, 'index'])->name('storehouses.index');

// Orders
Route::resource('orders', OrderController::class);
Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::post('orders/{order}/toggle-status', [OrderController::class, 'toggleStatus'])->name('orders.toggle-status');

// Clients
Route::resource('clients', ClientController::class);
Route::resource('clients/{client}/client-discounts', ClientDiscountController::class);

// Client Debts
Route::get('client-debts', [ClientDebtController::class, 'index'])->name('client-debts.index');
Route::get('clients/{client}/debts', [ClientDebtController::class, 'show'])->name('client-debts.show');

// Nomenclatures
Route::resource('nomenclatures', NomenclatureController::class);

// Nomenclature arrivals
Route::resource('nomenclature-arrivals', NomenclatureArrivalController::class);

// Mixture Compositions
Route::resource('mixture-compositions', MixtureCompositionController::class);

// Mixture Compositions Items
Route::resource('/mixture-compositions/{mixture_composition}/mixture-composition-items', MixtureCompositionItemController::class);

// Users
Route::post('/users/{user}/toggle-activity', [UserController::class, 'toggleActivity'])->name('users.toggle_activity');
Route::resource('users', UserController::class);
