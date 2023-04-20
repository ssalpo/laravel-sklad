<?php

use App\Http\Controllers\Admin\AutocompleteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PwaController;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware(['auth:sanctum', 'user.activity.check', 'admin'])->group(
    __DIR__ . '/admin.php'
);

// Auth
Route::controller(LoginController::class)->group(static function () {
    Route::get('/', 'create')->name('login')->middleware('guest');
    Route::post('login', 'store')->middleware('guest');
    Route::delete('logout', 'destroy')->name('logout');
});

// Profile routes

Route::middleware(['auth:sanctum', 'user.activity.check', 'applicant'])
    ->name('my.')
    ->prefix('my')
    ->group(__DIR__ . '/profile.php');

// Autocomplete routes
Route::group(['prefix' => 'autocomplete', 'as' => 'autocomplete.'], static function () {
    Route::get('clients', [AutocompleteController::class, 'clients'])->name('clients');
    Route::get('nomenclatures', [AutocompleteController::class, 'nomenclatures'])->name('nomenclatures');
    Route::get('orders', [AutocompleteController::class, 'orders'])->name('orders');
    Route::get('users', [AutocompleteController::class, 'users'])->name('users');
})->middleware(['auth:sanctum', 'user.activity.check']);

Route::group(['prefix' => 'pwa', 'as' => 'pwa.'], static function () {
    Route::get('manifest', [PwaController::class, 'manifest'])->name('manifest');
});

