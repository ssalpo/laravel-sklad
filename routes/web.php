<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware(['auth:sanctum', 'user.activity.check', 'admin'])->group(__DIR__ . '/admin.php'
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


