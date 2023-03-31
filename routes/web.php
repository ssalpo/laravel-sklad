<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientDiscountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MixtureCompositionController;
use App\Http\Controllers\MixtureCompositionItemController;
use App\Http\Controllers\NomenclatureArrivalController;
use App\Http\Controllers\NomenclatureController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StorehouseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\My\OrderController as MyOrderController;
use App\Http\Controllers\My\ClientController as MyClientController;

Route::middleware(['auth:sanctum', 'user.activity.check', 'admin'])->group(static function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('storehouses', [StorehouseController::class, 'index'])->name('storehouses.index');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('orders/{order}/toggle-status', [OrderController::class, 'toggleStatus'])->name('orders.toggle-status');

    // Clients
    Route::resource('clients', ClientController::class);
    Route::resource('clients/{client}/client-discounts', ClientDiscountController::class);

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
});

Route::middleware(['auth:sanctum', 'user.activity.check', 'applicant'])
    ->name('my.')
    ->prefix('my')
    ->group(static function () {
        Route::resource('orders', MyOrderController::class);

        Route::resource('clients', MyClientController::class);
    });

// Auth
Route::controller(LoginController::class)->group(static function () {
    Route::get('/', 'create')->name('login')->middleware('guest');
    Route::post('login', 'store')->middleware('guest');
    Route::delete('logout', 'destroy')->name('logout');
});

