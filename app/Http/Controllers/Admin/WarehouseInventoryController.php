<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseInventoryRequest;
use App\Models\Nomenclature;
use App\Models\WarehouseInventory;
use App\Models\WarehouseInventoryItem;
use App\Services\Toast;
use App\Services\UnitConvertor;
use App\Services\WarehouseInventoryService;

class WarehouseInventoryController extends Controller
{
    public function index()
    {
        $this->ensureWarehouseMovementsEnabled();
        $filters = request()->only(['status', 'from', 'to']);
        $inventories = WarehouseInventory::query()->with(['createdBy'])->withCount('items')
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->orderByDesc('id')->paginate()->withQueryString()
            ->through(fn (WarehouseInventory $inventory) => [
                'id' => $inventory->id,
                'status' => $inventory->status,
                'status_label' => WarehouseInventory::statusLabels()[$inventory->status],
                'items_count' => $inventory->items_count,
                'comment' => $inventory->comment,
                'created_at' => $inventory->created_at->format('d.m.Y H:i'),
                'posted_at' => $inventory->posted_at?->format('d.m.Y H:i'),
                'created_by' => $inventory->createdBy?->name,
            ]);

        return inertia('WarehouseInventories/Index', [
            'inventories' => $inventories,
            'filters' => $filters,
            'statuses' => WarehouseInventory::statusLabels(),
        ]);
    }

    public function create()
    {
        $this->ensureWarehouseMovementsEnabled();

        return inertia('WarehouseInventories/Edit', $this->editProps());
    }

    public function store(WarehouseInventoryRequest $request, WarehouseInventoryService $warehouseInventories)
    {
        $this->ensureWarehouseMovementsEnabled();
        $inventory = $warehouseInventories->create($request->validated());

        Toast::success('Черновик инвентаризации создан.');

        return to_route('warehouse-inventories.edit', $inventory);
    }

    public function edit(WarehouseInventory $warehouseInventory)
    {
        $this->ensureWarehouseMovementsEnabled();
        $warehouseInventory->load(['items.nomenclature', 'createdBy']);

        return inertia('WarehouseInventories/Edit', $this->editProps($warehouseInventory));
    }

    public function update(
        WarehouseInventoryRequest $request,
        WarehouseInventory $warehouseInventory,
        WarehouseInventoryService $warehouseInventories,
    ) {
        $this->ensureWarehouseMovementsEnabled();
        $warehouseInventories->update($warehouseInventory, $request->validated());

        Toast::success('Черновик инвентаризации сохранён.');

        return to_route('warehouse-inventories.edit', $warehouseInventory);
    }

    public function destroy(WarehouseInventory $warehouseInventory, WarehouseInventoryService $warehouseInventories)
    {
        $this->ensureWarehouseMovementsEnabled();
        $warehouseInventories->delete($warehouseInventory);

        Toast::success('Черновик инвентаризации удалён.');

        return to_route('warehouse-inventories.index');
    }

    public function post(WarehouseInventory $warehouseInventory, WarehouseInventoryService $warehouseInventories)
    {
        $this->ensureWarehouseMovementsEnabled();
        $warehouseInventories->post($warehouseInventory);

        Toast::success('Инвентаризация проведена. Расхождения отражены в остатках.');

        return to_route('warehouse-inventories.edit', $warehouseInventory);
    }

    private function editProps(?WarehouseInventory $inventory = null): array
    {
        return [
            'inventory' => $inventory ? [
                'id' => $inventory->id,
                'status' => $inventory->status,
                'status_label' => WarehouseInventory::statusLabels()[$inventory->status],
                'comment' => $inventory->comment,
                'created_at' => $inventory->created_at->format('d.m.Y H:i'),
                'posted_at' => $inventory->posted_at?->format('d.m.Y H:i'),
                'created_by' => $inventory->createdBy?->name,
                'items' => $inventory->items->map(fn (WarehouseInventoryItem $item) => [
                    'nomenclature_id' => $item->nomenclature_id,
                    'nomenclature' => $item->nomenclature->name,
                    'unit' => UnitConvertor::UNIT_LABELS[$item->nomenclature->unit],
                    'book_quantity' => $item->book_quantity,
                    'actual_quantity' => $item->actual_quantity,
                ])->values(),
            ] : null,
            'nomenclatures' => Nomenclature::query()->saleType()->orderBy('name')->get(['id', 'name', 'unit'])
                ->map(fn (Nomenclature $nomenclature) => [
                    'id' => $nomenclature->id,
                    'name' => $nomenclature->name,
                    'unit' => UnitConvertor::UNIT_LABELS[$nomenclature->unit],
                ]),
        ];
    }

    private function ensureWarehouseMovementsEnabled(): void
    {
        abort_unless(config('warehouse.use_movements'), 404);
    }
}
