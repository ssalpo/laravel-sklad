<?php

namespace App\Http\Controllers\Admin;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Http\Controllers\Controller;
use App\Models\Nomenclature;
use App\Models\WarehouseInventory;
use App\Models\WarehouseMovement;
use App\Services\UnitConvertor;

class WarehouseMovementController extends Controller
{
    public function index()
    {
        abort_unless(config('warehouse.use_movements'), 404);

        $filters = request()->only(['from', 'to', 'nomenclature_id', 'type', 'direction']);
        $movements = WarehouseMovement::query()->with(['nomenclature', 'order', 'orderItem', 'createdBy'])
            ->when($filters['from'] ?? null, fn ($q, $date) => $q->whereDate('occurred_at', '>=', $date))
            ->when($filters['to'] ?? null, fn ($q, $date) => $q->whereDate('occurred_at', '<=', $date))
            ->when($filters['nomenclature_id'] ?? null, fn ($q, $id) => $q->where('nomenclature_id', $id))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->when($filters['direction'] ?? null, fn ($q, $direction) => $q->where('direction', $direction))
            ->orderByDesc('occurred_at')->orderByDesc('id')->paginate()->withQueryString()
            ->through(fn (WarehouseMovement $movement) => [
                'id' => $movement->id, 'occurred_at' => $movement->occurred_at->format('d.m.Y H:i'),
                'nomenclature' => $movement->nomenclature->name,
                'unit' => UnitConvertor::UNIT_LABELS[$movement->nomenclature->unit],
                'type' => $movement->type,
                'type_label' => WarehouseMovementType::label($movement->type),
                'direction' => $movement->direction,
                'quantity' => $movement->quantity, 'comment' => $movement->comment,
                'source' => $movement->order_id
                    ? "Заказ #{$movement->order_id}"
                    : ($movement->source_type === WarehouseInventory::class ? "Инвентаризация #{$movement->source_id}" : null),
                'created_by' => $movement->createdBy?->name,
            ]);

        return inertia('WarehouseMovements/Index', [
            'movements' => $movements, 'filters' => $filters,
            'nomenclatures' => Nomenclature::query()->orderBy('name')->get(['id', 'name']),
            'types' => WarehouseMovementType::labels(),
            'directions' => WarehouseMovementDirection::labels(),
        ]);
    }
}
