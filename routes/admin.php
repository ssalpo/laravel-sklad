<?php

use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\Cash\CashTransactionController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientDebtController;
use App\Http\Controllers\Admin\ClientDebtPaymentController;
use App\Http\Controllers\Admin\ClientDiscountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\Employee\EmployeeSalaryController;
use App\Http\Controllers\Admin\MixtureCompositionController;
use App\Http\Controllers\Admin\MixtureCompositionItemController;
use App\Http\Controllers\Admin\NomenclatureArrivalController;
use App\Http\Controllers\Admin\NomenclatureController;
use App\Http\Controllers\Admin\NomenclatureOperationController;
use App\Http\Controllers\Admin\NomenclatureRefundController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RawMaterial\RawMaterialController;
use App\Http\Controllers\Admin\RawMaterial\RawMaterialPaymentController;
use App\Http\Controllers\Admin\StorehouseController;
use App\Http\Controllers\Admin\TelegramNotificationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('analytics-in-range', [AnalyticController::class, 'range'])->name('analytics.range');

Route::get('storehouses', [StorehouseController::class, 'index'])->name('storehouses.index');

Route::get('cash-transactions/day-statistics', [CashTransactionController::class, 'dayStatistics'])->name('cash-transaction.day-statistics');
Route::resource('cash-transactions', CashTransactionController::class);
Route::post('cash-transactions/{cash_transaction}/dollar-exchange', [CashTransactionController::class, 'dollarExchange'])->name('cash-transaction.dollar-exchange');

// Orders
Route::resource('orders', OrderController::class);
Route::post('orders/{order}/toggle-status', [OrderController::class, 'toggleStatus'])->name('orders.toggle-status');
Route::post('/orders/{order}/mark-as-send', [OrderController::class, 'markAsSend'])->name('orders.mark-as-send');
Route::post('/orders/{order}/mark-as-cancel', [OrderController::class, 'markAsCancel'])->name('orders.mark-as-cancel');
Route::post('/orders/{order}/do-payment', [OrderController::class, 'doPayment'])->name('orders.do-payment');
Route::get('order-invoices', [OrderController::class, 'invoices'])->name('order-invoices');

// Clients
Route::resource('clients', ClientController::class);
Route::resource('clients/{client}/client-discounts', ClientDiscountController::class);

// Client Debts
Route::get('all-client-debts', [ClientDebtController::class, 'allClientDebts'])->name('all-client-debts');
Route::resource('/clients/{client}/debts', ClientDebtController::class, ['as' => 'client']);
Route::resource('/clients/{client}/debts/{debt}/payments', ClientDebtPaymentController::class, ['as' => 'client.debts']);

// Nomenclatures
Route::post('nomenclatures/change-markups', [NomenclatureController::class, 'changeMarkups'])->name('nomenclatures.change-markups');
Route::resource('nomenclatures', NomenclatureController::class);

// Nomenclatures Operations
Route::get('nomenclatures-operations/withdraw', [NomenclatureOperationController::class, 'withdrawIndex'])->name('nomenclature-operations.index-withdraw');
Route::post('nomenclatures-operations/refund-order', [NomenclatureOperationController::class, 'refundOrder'])->name('nomenclature-operations.order-refund');
Route::resource('nomenclature-operations', NomenclatureOperationController::class);

Route::get('nomenclature-refunds', [NomenclatureRefundController::class, 'index'])->name('nomenclature-refunds.index');

// Nomenclature arrivals
Route::resource('nomenclature-arrivals', NomenclatureArrivalController::class);

// Mixture Compositions
Route::resource('mixture-compositions', MixtureCompositionController::class);

// Mixture Compositions Items
Route::resource('/mixture-compositions/{mixture_composition}/mixture-composition-items', MixtureCompositionItemController::class);

// Users
Route::post('/users/{user}/toggle-admin-status', [UserController::class, 'toggleAdminStatus'])->name('users.toggle-admin-status');
Route::post('/users/{user}/toggle-activity', [UserController::class, 'toggleActivity'])->name('users.toggle_activity');
Route::get('/users/{user}/telegram-notifications', [TelegramNotificationController::class, 'index'])->name('users.telegram-notifications.index');
Route::post('/users/{user}/telegram-notifications/toggle', [TelegramNotificationController::class, 'toggle'])->name('users.telegram-notifications.toggle');
Route::resource('users', UserController::class);

// Raw Materials
Route::resource('raw-materials', RawMaterialController::class);

// Raw Material Payments
Route::resource('raw-materials.raw-material-payments', RawMaterialPaymentController::class);

// Employee
Route::resource('employees', EmployeeController::class);

// Employee Salary
Route::resource('employees.employee-salaries', EmployeeSalaryController::class);
