<?php

namespace App\Services;

use App\Models\NomenclatureOperation;

class NomenclatureOperationService extends BaseService
{
    public function store(array $data)
    {
        return NomenclatureOperation::create(
            NomenclatureService::mergeNomenclaturePrices(
                $data['nomenclature_id'],
                $data
            )
        );
    }

    public function update(int $id, array $data)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if($nomenclatureOperation->can_edit) {
            $nomenclatureOperation->update(
                NomenclatureService::mergeNomenclaturePrices(
                    $data['nomenclature_id'],
                    $data
                )
            );
        }

        return $nomenclatureOperation;
    }

    public function delete(int $id)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if($nomenclatureOperation->can_edit) {
            $nomenclatureOperation->delete();
        }

        return $nomenclatureOperation;
    }
}
