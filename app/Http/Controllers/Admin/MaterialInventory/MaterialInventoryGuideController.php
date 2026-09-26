<?php

namespace App\Http\Controllers\Admin\MaterialInventory;

use App\Enums\MaterialInventoryMovementDirection;
use App\Enums\MaterialInventoryMovementType;
use App\Http\Controllers\Controller;
use App\Models\InventoryCount;
use App\Models\ProductionRun;

class MaterialInventoryGuideController extends Controller
{
    public function index()
    {
        return inertia('MaterialInventory/Guide', [
            'movementTypes' => MaterialInventoryMovementType::details(),
            'directions' => [
                ['value' => MaterialInventoryMovementDirection::IN, 'label' => 'Увеличение остатка', 'description' => 'Количество материала добавляется к текущему остатку.'],
                ['value' => MaterialInventoryMovementDirection::OUT, 'label' => 'Уменьшение остатка', 'description' => 'Количество материала вычитается из текущего остатка.'],
            ],
            'productionStatuses' => ProductionRun::statusDetails(),
            'inventoryCountStatuses' => InventoryCount::statusDetails(),
        ]);
    }
}
