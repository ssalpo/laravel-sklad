<?php

namespace App\Services;

use App\Enums\MaterialInventoryMovementDirection;
use App\Enums\MaterialInventoryMovementType;
use App\Models\MaterialInventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MaterialInventoryMovementService
{
    public function __construct(private MaterialInventoryStockService $stock)
    {
    }

    public function income(array $attributes): MaterialInventoryMovement
    {
        return $this->record($attributes, MaterialInventoryMovementDirection::IN);
    }

    public function expense(array $attributes): MaterialInventoryMovement
    {
        return $this->record($attributes, MaterialInventoryMovementDirection::OUT);
    }

    public function reverse(MaterialInventoryMovement $movement, ?string $comment = null): MaterialInventoryMovement
    {
        return DB::transaction(function () use ($movement, $comment): MaterialInventoryMovement {
            $movement = MaterialInventoryMovement::query()->lockForUpdate()->findOrFail($movement->id);
            if ($movement->reversals()->exists()) {
                throw ValidationException::withMessages(['movement' => 'Движение уже сторнировано.']);
            }
            $direction = $movement->direction === MaterialInventoryMovementDirection::IN
                ? MaterialInventoryMovementDirection::OUT : MaterialInventoryMovementDirection::IN;

            return $this->record([
                'nomenclature_id' => $movement->nomenclature_id, 'type' => MaterialInventoryMovementType::REVERSAL,
                'quantity' => $movement->quantity, 'source_type' => $movement->source_type, 'source_id' => $movement->source_id,
                'parent_id' => $movement->id, 'comment' => $comment ?: "Отмена движения #{$movement->id}", 'occurred_at' => now(),
            ], $direction);
        });
    }

    private function record(array $attributes, string $direction): MaterialInventoryMovement
    {
        $attributes['quantity'] = $this->positiveQuantity($attributes['quantity']);
        if ($direction === MaterialInventoryMovementDirection::OUT) {
            $this->stock->ensureAvailable((int) $attributes['nomenclature_id'], $attributes['quantity']);
        }
        $attributes['direction'] = $direction;
        $attributes['occurred_at'] = $attributes['occurred_at'] ?? now();
        $attributes['created_by'] = $attributes['created_by'] ?? auth()->id();

        return MaterialInventoryMovement::create($attributes);
    }

    private function positiveQuantity(mixed $quantity): string
    {
        $value = bcadd((string) $quantity, '0', 6);
        if (bccomp($value, '0', 6) !== 1) {
            throw ValidationException::withMessages(['quantity' => 'Количество должно быть больше нуля.']);
        }

        return $value;
    }
}
