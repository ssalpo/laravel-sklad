<?php

namespace App\Services;

use App\Models\NomenclatureArrival;

class NomenclatureArrivalService
{
    public function store(array $data)
    {
        return NomenclatureArrival::create(
            NomenclatureService::mergeNomenclaturePrices(
                $data['nomenclature_id'],
                $data
            )
        );
    }

    public function update(int $id, array $data)
    {
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
        $nomenclatureArrival = NomenclatureArrival::findOrFail($id);

        if($nomenclatureArrival->can_edit) {
            $nomenclatureArrival->delete();
        }

        return $nomenclatureArrival;
    }
}
