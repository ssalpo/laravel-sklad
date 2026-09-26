<?php

namespace App\Http\Controllers\Admin;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Http\Controllers\Controller;
use App\Models\WarehouseInventory;

class WarehouseGuideController extends Controller
{
    public function index()
    {
        abort_unless(config('warehouse.use_movements'), 404);

        return inertia('Warehouse/Guide', [
            'movementTypes' => WarehouseMovementType::details(),
            'directions' => [
                ['value' => WarehouseMovementDirection::IN, 'label' => 'Увеличение остатка', 'description' => 'Количество товара добавляется к текущему остатку склада.'],
                ['value' => WarehouseMovementDirection::OUT, 'label' => 'Уменьшение остатка', 'description' => 'Количество товара вычитается из текущего остатка склада.'],
            ],
            'inventoryStatuses' => WarehouseInventory::statusDetails(),
        ]);
    }
}
