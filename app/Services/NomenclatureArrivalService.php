<?php

namespace App\Services;

use App\Models\NomenclatureArrival;
use App\Models\WarehouseMovement;
use App\Enums\WarehouseMovementType;

class NomenclatureArrivalService
{
    public function __construct(private WarehouseMovementService $warehouseMovements)
    {
    }

    public function store(array $data)
    {
        if (config('warehouse.use_movements')) {
            return $this->warehouseMovements->income(array_merge(
                NomenclatureService::mergeNomenclaturePrices($data['nomenclature_id'], $data),
                ['type' => WarehouseMovementType::PURCHASE, 'occurred_at' => $data['arrival_at'] ?? now()]
            ));
        }

        return NomenclatureArrival::create(
            NomenclatureService::mergeNomenclaturePrices(
                $data['nomenclature_id'],
                $data
            )
        );
    }

    public function update(int $id, array $data)
    {
        if (config('warehouse.use_movements')) {
            $movement = WarehouseMovement::query()->where('type', WarehouseMovementType::PURCHASE)->findOrFail($id);

            return $this->warehouseMovements->correct($movement, array_merge(
                NomenclatureService::mergeNomenclaturePrices($data['nomenclature_id'], $data),
                ['type' => WarehouseMovementType::PURCHASE, 'occurred_at' => $data['arrival_at'] ?? $movement->occurred_at]
            ));
        }

        $nomenclatureArrival = NomenclatureArrival::findOrFail($id);

        if($nomenclatureArrival->can_edit) {
            $nomenclatureArrival->update(
                NomenclatureService::mergeNomenclaturePrices(
                    $data['nomenclature_id'],
                    $data
                )
            );
        }

        return $nomenclatureArrival;
    }

    public function destroy(int $id)
    {
        if (config('warehouse.use_movements')) {
            $movement = WarehouseMovement::query()->where('type', WarehouseMovementType::PURCHASE)->findOrFail($id);

            return $this->warehouseMovements->reverse($movement, 'Отмена ручного прихода');
        }

        $nomenclatureArrival = NomenclatureArrival::findOrFail($id);

        if($nomenclatureArrival->can_edit) {
            $nomenclatureArrival->delete();
        }

        return $nomenclatureArrival;
    }
}
