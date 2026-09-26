<?php

namespace App\Services;

use App\Enums\MaterialInventoryMovementType;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryCountService
{
    public function __construct(private MaterialInventoryStockService $stock, private MaterialInventoryMovementService $movements)
    {
    }

    public function create(array $data): InventoryCount
    {
        return DB::transaction(function () use ($data): InventoryCount {
            $count = InventoryCount::create(['comment' => $data['comment'] ?? null, 'started_at' => now(), 'created_by' => auth()->id()]);
            $this->syncItems($count, $data['items']);

            return $count;
        });
    }

    public function update(InventoryCount $count, array $data): InventoryCount
    {
        return DB::transaction(function () use ($count, $data): InventoryCount {
            $count = InventoryCount::query()->lockForUpdate()->findOrFail($count->id);
            $this->ensureDraft($count);
            $count->update(['comment' => $data['comment'] ?? null]);
            $this->syncItems($count, $data['items']);

            return $count;
        });
    }

    public function complete(InventoryCount $count): InventoryCount
    {
        return DB::transaction(function () use ($count): InventoryCount {
            $count = InventoryCount::query()->lockForUpdate()->with('items')->findOrFail($count->id);
            $this->ensureDraft($count);
            if ($count->items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'Добавьте хотя бы один материал.']);
            }
            foreach ($count->items as $item) {
                $difference = bcsub($item->actual_quantity, $item->book_quantity, 6);
                if (bccomp($difference, '0', 6) === 0) {
                    continue;
                }
                $attributes = ['nomenclature_id' => $item->nomenclature_id, 'quantity' => ltrim($difference, '-'), 'source_type' => InventoryCount::class, 'source_id' => $count->id, 'comment' => "Инвентаризация #{$count->id}", 'occurred_at' => now()];
                if (bccomp($difference, '0', 6) === 1) {
                    $this->movements->income($attributes + ['type' => MaterialInventoryMovementType::INVENTORY_SURPLUS]);
                } else {
                    $this->movements->expense($attributes + ['type' => MaterialInventoryMovementType::INVENTORY_SHORTAGE]);
                }
            }
            $count->update(['status' => InventoryCount::STATUS_COMPLETED, 'completed_at' => now(), 'completed_by' => auth()->id()]);

            return $count->refresh();
        });
    }

    public function delete(InventoryCount $count): void
    {
        $this->ensureDraft($count);
        $count->delete();
    }

    private function syncItems(InventoryCount $count, array $items): void
    {
        $existing = $count->items()->get()->keyBy('nomenclature_id');
        $ids = collect($items)->pluck('nomenclature_id')->map(fn ($id) => (int) $id);
        $count->items()->whereNotIn('nomenclature_id', $ids)->delete();
        foreach ($items as $item) {
            $existingItem = $existing->get((int) $item['nomenclature_id']);
            if ($existingItem) {
                $existingItem->update(['actual_quantity' => bcadd((string) $item['actual_quantity'], '0', 6)]);

                continue;
            }
            InventoryCountItem::create(['inventory_count_id' => $count->id, 'nomenclature_id' => $item['nomenclature_id'], 'book_quantity' => $this->stock->getBalance((int) $item['nomenclature_id']), 'actual_quantity' => bcadd((string) $item['actual_quantity'], '0', 6)]);
        }
    }

    private function ensureDraft(InventoryCount $count): void
    {
        if ($count->status !== InventoryCount::STATUS_DRAFT) {
            throw ValidationException::withMessages(['count' => 'Проведённую инвентаризацию нельзя изменять.']);
        }
    }
}
