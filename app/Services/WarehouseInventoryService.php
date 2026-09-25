<?php

namespace App\Services;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Models\WarehouseInventory;
use App\Models\WarehouseInventoryItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarehouseInventoryService
{
    public function __construct(
        private WarehouseStockService $warehouseStock,
        private WarehouseMovementService $warehouseMovements,
    ) {
    }

    public function create(array $data): WarehouseInventory
    {
        return DB::transaction(function () use ($data): WarehouseInventory {
            $inventory = WarehouseInventory::create([
                'comment' => $data['comment'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $this->syncItems($inventory, $data['items']);

            return $inventory;
        });
    }

    public function update(WarehouseInventory $inventory, array $data): WarehouseInventory
    {
        return DB::transaction(function () use ($inventory, $data): WarehouseInventory {
            $inventory = WarehouseInventory::query()->lockForUpdate()->findOrFail($inventory->id);
            $this->ensureDraft($inventory);
            $inventory->update(['comment' => $data['comment'] ?? null]);
            $this->syncItems($inventory, $data['items']);

            return $inventory->refresh();
        });
    }

    public function post(WarehouseInventory $inventory): WarehouseInventory
    {
        return DB::transaction(function () use ($inventory): WarehouseInventory {
            $inventory = WarehouseInventory::query()->lockForUpdate()->with('items')->findOrFail($inventory->id);
            $this->ensureDraft($inventory);

            if ($inventory->items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'В инвентаризации должна быть хотя бы одна позиция.']);
            }

            foreach ($inventory->items as $item) {
                $difference = bcsub($item->actual_quantity, $item->book_quantity, 6);

                if (bccomp($difference, '0', 6) === 0) {
                    continue;
                }

                $direction = bccomp($difference, '0', 6) === 1
                    ? WarehouseMovementDirection::IN
                    : WarehouseMovementDirection::OUT;

                $record = [
                    'nomenclature_id' => $item->nomenclature_id,
                    'type' => $direction === WarehouseMovementDirection::IN
                        ? WarehouseMovementType::INVENTORY_IN
                        : WarehouseMovementType::INVENTORY_OUT,
                    'quantity' => ltrim($difference, '-'),
                    'source_type' => WarehouseInventory::class,
                    'source_id' => $inventory->id,
                    'comment' => "Инвентаризация #{$inventory->id}",
                    'occurred_at' => now(),
                ];

                $direction === WarehouseMovementDirection::IN
                    ? $this->warehouseMovements->income($record)
                    : $this->warehouseMovements->expense($record);
            }

            $inventory->update(['status' => WarehouseInventory::STATUS_POSTED, 'posted_at' => now()]);

            return $inventory->refresh();
        });
    }

    public function delete(WarehouseInventory $inventory): void
    {
        DB::transaction(function () use ($inventory): void {
            $inventory = WarehouseInventory::query()->lockForUpdate()->findOrFail($inventory->id);
            $this->ensureDraft($inventory);
            $inventory->delete();
        });
    }

    private function syncItems(WarehouseInventory $inventory, array $items): void
    {
        $existing = $inventory->items()->get()->keyBy('nomenclature_id');
        $ids = collect($items)->pluck('nomenclature_id')->map(fn ($id) => (int) $id);

        $inventory->items()->whereNotIn('nomenclature_id', $ids)->delete();

        foreach ($items as $item) {
            $nomenclatureId = (int) $item['nomenclature_id'];
            $actualQuantity = bcadd((string) $item['actual_quantity'], '0', 6);
            $existingItem = $existing->get($nomenclatureId);

            if ($existingItem) {
                $existingItem->update(['actual_quantity' => $actualQuantity]);

                continue;
            }

            WarehouseInventoryItem::create([
                'warehouse_inventory_id' => $inventory->id,
                'nomenclature_id' => $nomenclatureId,
                'book_quantity' => $this->warehouseStock->getBalance($nomenclatureId),
                'actual_quantity' => $actualQuantity,
            ]);
        }
    }

    private function ensureDraft(WarehouseInventory $inventory): void
    {
        if ($inventory->status !== WarehouseInventory::STATUS_DRAFT) {
            throw ValidationException::withMessages(['inventory' => 'Проведённую инвентаризацию нельзя изменять.']);
        }
    }
}
