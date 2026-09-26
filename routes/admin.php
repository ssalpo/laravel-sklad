<?php

use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\Cash\CashTransactionController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientDebtController;
use App\Http\Controllers\Admin\ClientDebtPaymentController;
use App\Http\Controllers\Admin\ClientDiscountController;
use App\Http\Controllers\Admin\ClientPriceTemplateController;
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
use App\Http\Controllers\Admin\WarehouseMovementController;
use App\Http\Controllers\Admin\WarehouseGuideController;
use App\Http\Controllers\Admin\WarehouseInventoryController;
use App\Http\Controllers\Admin\MaterialInventory\InventoryCountController;
use App\Http\Controllers\Admin\MaterialInventory\MaterialInventoryGuideController;
use App\Http\Controllers\Admin\MaterialInventory\MaterialInventoryController;
use App\Http\Controllers\Admin\MaterialInventory\ProductionRecipeController;
use App\Http\Controllers\Admin\MaterialInventory\ProductionRunController;
use App\Http\Controllers\Admin\TelegramNotificationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('analytics-in-range', [AnalyticController::class, 'range'])->name('analytics.range');

Route::get('storehouses', [StorehouseController::class, 'index'])->name('storehouses.index');
Route::get('warehouse-movements', [WarehouseMovementController::class, 'index'])->name('warehouse-movements.index');
Route::get('warehouse-guide', [WarehouseGuideController::class, 'index'])->name('warehouse-guide.index');
Route::post('warehouse-inventories/{warehouse_inventory}/post', [WarehouseInventoryController::class, 'post'])->name('warehouse-inventories.post');
Route::resource('warehouse-inventories', WarehouseInventoryController::class)->except(['show']);

// Material inventory and production are independent from the finished-goods warehouse.
Route::get('material-inventory', [MaterialInventoryController::class, 'balances'])->name('material-inventory.balances');
Route::get('material-inventory/guide', [MaterialInventoryGuideController::class, 'index'])->name('material-inventory.guide');
Route::get('material-inventory/movements', [MaterialInventoryController::class, 'movements'])->name('material-inventory.movements');
Route::get('material-inventory/receipt', [MaterialInventoryController::class, 'createReceipt'])->name('material-inventory.receipt.create');
Route::post('material-inventory/receipt', [MaterialInventoryController::class, 'storeReceipt'])->name('material-inventory.receipt.store');
Route::get('material-inventory/adjustment-in', [MaterialInventoryController::class, 'createAdjustmentIn'])->name('material-inventory.adjustment-in.create');
Route::post('material-inventory/adjustment-in', [MaterialInventoryController::class, 'storeAdjustmentIn'])->name('material-inventory.adjustment-in.store');
Route::get('material-inventory/adjustment-out', [MaterialInventoryController::class, 'createAdjustmentOut'])->name('material-inventory.adjustment-out.create');
Route::post('material-inventory/adjustment-out', [MaterialInventoryController::class, 'storeAdjustmentOut'])->name('material-inventory.adjustment-out.store');
Route::get('material-inventory/write-off', [MaterialInventoryController::class, 'createWriteOff'])->name('material-inventory.write-off.create');
Route::post('material-inventory/write-off', [MaterialInventoryController::class, 'storeWriteOff'])->name('material-inventory.write-off.store');
Route::get('material-inventory/return', [MaterialInventoryController::class, 'createReturn'])->name('material-inventory.return.create');
Route::post('material-inventory/return', [MaterialInventoryController::class, 'storeReturn'])->name('material-inventory.return.store');

Route::post('production-recipes/{production_recipe}/activate', [ProductionRecipeController::class, 'activate'])->name('production-recipes.activate');
Route::post('production-recipes/{production_recipe}/versions', [ProductionRecipeController::class, 'createVersion'])->name('production-recipes.versions.store');
Route::resource('production-recipes', ProductionRecipeController::class)->except(['show', 'destroy']);
Route::post('production-runs/{production_run}/complete', [ProductionRunController::class, 'complete'])->name('production-runs.complete');
Route::post('production-runs/{production_run}/cancel', [ProductionRunController::class, 'cancel'])->name('production-runs.cancel');
Route::resource('production-runs', ProductionRunController::class)->except(['destroy']);
Route::post('inventory-counts/{inventory_count}/complete', [InventoryCountController::class, 'complete'])->name('inventory-counts.complete');
Route::resource('inventory-counts', InventoryCountController::class)->except(['show']);

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
Route::resource('clients/{client}/client-price-templates', ClientPriceTemplateController::class)
    ->only(['index', 'store', 'update', 'destroy']);

// Client Debts
Route::get('all-client-debts', [ClientDebtController::class, 'allClientDebts'])->name('all-client-debts');
Route::resource('/clients/{client}/debts', ClientDebtController::class, ['as' => 'client']);
Route::put('/clients/{client}/debts/{debt}/payments/{payment}/update-comment', [ClientDebtPaymentController::class, 'updateComment'])->name('client.debts.payments.change-comment');
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
