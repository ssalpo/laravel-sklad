<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MixtureCompositionController;
use App\Http\Controllers\MixtureCompositionItemController;
use App\Http\Controllers\NomenclatureArrivalController;
use App\Http\Controllers\NomenclatureController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'user.activity.check'])->group(static function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Clients
    Route::resource('clients', ClientController::class);

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

// Auth
Route::controller(LoginController::class)->group(static function () {
    Route::get('/', 'create')->name('login')->middleware('guest');
    Route::post('login', 'store')->middleware('guest');
    Route::delete('logout', 'destroy')->name('logout');
});

