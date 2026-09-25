<?php

namespace App\Services;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Models\WarehouseMovement;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarehouseMovementService
{
    public function income(array $attributes): WarehouseMovement
    {
        return $this->record($attributes, WarehouseMovementDirection::IN);
    }

    public function expense(array $attributes): WarehouseMovement
    {
        return $this->record($attributes, WarehouseMovementDirection::OUT);
    }

    public function update(WarehouseMovement $movement, array $attributes): WarehouseMovement
    {
        if ($movement->reversals()->exists()) {
            throw ValidationException::withMessages(['movement' => 'Нельзя изменять движение, для которого уже создана корректировка.']);
        }

        $attributes['quantity'] = $this->positiveQuantity($attributes['quantity']);
        $movement->update($attributes);

        return $movement->refresh();
    }

    public function correct(WarehouseMovement $movement, array $attributes): WarehouseMovement
    {
        return DB::transaction(function () use ($movement, $attributes) {
            $movement = WarehouseMovement::lockForUpdate()->findOrFail($movement->id);
            $this->reverse($movement, "Корректировка движения #{$movement->id}");

            return $this->record(array_merge([
                'nomenclature_id' => $movement->nomenclature_id,
                'type' => $movement->type,
                'quantity' => $movement->quantity,
                'price' => $movement->price,
                'price_for_sale' => $movement->price_for_sale,
                'source_type' => $movement->source_type,
                'source_id' => $movement->source_id,
                'order_id' => $movement->order_id,
                'order_item_id' => $movement->order_item_id,
                'comment' => $movement->comment,
                'occurred_at' => now(),
            ], $attributes), $movement->direction);
        });
    }

    public function reverse(WarehouseMovement $movement, ?string $comment = null, ?string $quantity = null): WarehouseMovement
    {
        return DB::transaction(function () use ($movement, $comment) {
            $movement = WarehouseMovement::lockForUpdate()->findOrFail($movement->id);

            if ($movement->reversals()->exists()) {
                throw ValidationException::withMessages(['movement' => 'Движение уже сторнировано.']);
            }

            $quantity = $this->positiveQuantity($quantity ?? $movement->quantity);
            if (bccomp($quantity, $movement->quantity, 6) === 1) {
                throw ValidationException::withMessages(['quantity' => 'Количество сторно превышает исходное движение.']);
            }

            return $this->record([
                'nomenclature_id' => $movement->nomenclature_id,
                'type' => WarehouseMovementType::REVERSAL,
                'quantity' => $quantity,
                'price' => $movement->price,
                'price_for_sale' => $movement->price_for_sale,
                'source_type' => $movement->source_type,
                'source_id' => $movement->source_id,
                'order_id' => $movement->order_id,
                'order_item_id' => $movement->order_item_id,
                'parent_id' => $movement->id,
                'comment' => $comment ?: "Сторно движения #{$movement->id}",
                'occurred_at' => now(),
            ], $movement->direction === WarehouseMovementDirection::IN
                ? WarehouseMovementDirection::OUT
                : WarehouseMovementDirection::IN);
        });
    }

    public function importLegacy(array $attributes, string $direction): ?WarehouseMovement
    {
        $exists = WarehouseMovement::withTrashed()
            ->where('legacy_source_type', $attributes['legacy_source_type'])
            ->where('legacy_source_id', $attributes['legacy_source_id'])
            ->exists();

        return $exists ? null : $this->record($attributes, $direction);
    }

    private function record(array $attributes, string $direction): WarehouseMovement
    {
        $attributes['quantity'] = $this->positiveQuantity($attributes['quantity']);
        $attributes['direction'] = $direction;
        $attributes['occurred_at'] = $attributes['occurred_at'] ?? now();
        $attributes['created_by'] = $attributes['created_by'] ?? auth()->id();

        return WarehouseMovement::create($attributes);
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
