<?php

namespace App\Http\Controllers\Admin\MaterialInventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialInventory\InventoryCountRequest;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\Nomenclature;
use App\Services\InventoryCountService;
use App\Services\Toast;
use App\Services\UnitConvertor;

class InventoryCountController extends Controller
{
    public function index()
    {
        $counts = InventoryCount::query()->with('createdBy')->withCount('items')->latest('id')->paginate()->withQueryString()->through(fn (InventoryCount $count) => ['id' => $count->id, 'status' => $count->status, 'items_count' => $count->items_count, 'comment' => $count->comment, 'started_at' => $count->started_at->format('d.m.Y H:i'), 'completed_at' => $count->completed_at?->format('d.m.Y H:i'), 'created_by' => $count->createdBy?->name]);

        return inertia('InventoryCounts/Index', ['counts' => $counts, 'statusLabels' => InventoryCount::statusLabels()]);
    }

    public function create()
    {
        return inertia('InventoryCounts/Edit', $this->editProps());
    }

    public function store(InventoryCountRequest $request, InventoryCountService $service)
    {
        $count = $service->create($request->validated());
        Toast::success('Черновик инвентаризации создан.');

        return to_route('inventory-counts.edit', $count);
    }

    public function edit(InventoryCount $inventoryCount)
    {
        $inventoryCount->load('items.nomenclature');

        return inertia('InventoryCounts/Edit', $this->editProps($inventoryCount));
    }

    public function update(InventoryCount $inventoryCount, InventoryCountRequest $request, InventoryCountService $service)
    {
        $service->update($inventoryCount, $request->validated());
        Toast::success('Черновик инвентаризации сохранён.');

        return to_route('inventory-counts.edit', $inventoryCount);
    }

    public function destroy(InventoryCount $inventoryCount, InventoryCountService $service)
    {
        $service->delete($inventoryCount);
        Toast::success('Черновик инвентаризации удалён.');

        return to_route('inventory-counts.index');
    }

    public function complete(InventoryCount $inventoryCount, InventoryCountService $service)
    {
        $service->complete($inventoryCount);
        Toast::success('Инвентаризация проведена.');

        return to_route('inventory-counts.edit', $inventoryCount);
    }

    private function editProps(?InventoryCount $count = null): array
    {
        return ['count' => $count ? ['id' => $count->id, 'status' => $count->status, 'comment' => $count->comment, 'items' => $count->items->map(fn (InventoryCountItem $item) => ['nomenclature_id' => $item->nomenclature_id, 'nomenclature' => $item->nomenclature->name, 'unit' => UnitConvertor::UNIT_LABELS[$item->nomenclature->unit], 'book_quantity' => $item->book_quantity, 'actual_quantity' => $item->actual_quantity])->values()] : null,
            'materials' => Nomenclature::query()->compositeType()->orderBy('name')->get(['id', 'name', 'unit'])->map(fn ($m) => ['id' => $m->id, 'name' => $m->name, 'unit' => UnitConvertor::UNIT_LABELS[$m->unit]])->all()];
    }
}
