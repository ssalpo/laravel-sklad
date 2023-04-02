<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientDebtController;
use App\Http\Controllers\Admin\ClientDebtPaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MixtureCompositionController;
use App\Http\Controllers\Admin\MixtureCompositionItemController;
use App\Http\Controllers\Admin\NomenclatureArrivalController;
use App\Http\Controllers\Admin\NomenclatureController;
use App\Http\Controllers\Admin\NomenclatureOperationController;
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

// Client Debts
Route::get('all-client-debts', [ClientDebtController::class, 'allClientDebts'])->name('all-client-debts');
Route::resource('/clients/{client}/debts', ClientDebtController::class, ['as' => 'client']);
Route::resource('/clients/{client}/debts/{debt}/payments', ClientDebtPaymentController::class, ['as' => 'client.debts']);

// Nomenclatures
Route::resource('nomenclatures', NomenclatureController::class);

// Nomenclatures Operations
Route::get('nomenclatures-operations/withdraw', [NomenclatureOperationController::class, 'withdrawIndex'])->name('nomenclature-operations.index-withdraw');
Route::resource('nomenclature-operations', NomenclatureOperationController::class);

// Nomenclature arrivals
Route::resource('nomenclature-arrivals', NomenclatureArrivalController::class);

// Mixture Compositions
Route::resource('mixture-compositions', MixtureCompositionController::class);

// Mixture Compositions Items
Route::resource('/mixture-compositions/{mixture_composition}/mixture-composition-items', MixtureCompositionItemController::class);

// Users
Route::post('/users/{user}/toggle-activity', [UserController::class, 'toggleActivity'])->name('users.toggle_activity');
Route::resource('users', UserController::class);
