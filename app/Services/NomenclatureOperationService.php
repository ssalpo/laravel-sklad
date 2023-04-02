<?php

namespace App\Services;

use App\Models\NomenclatureOperation;

class NomenclatureOperationService extends BaseService
{
    public function store(array $data)
    {
        return NomenclatureOperation::create($data);
    }

    public function update(int $id, array $data)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        $nomenclatureOperation->update($data);

        return $nomenclatureOperation;
    }

    public function delete(int $id)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        $nomenclatureOperation->delete();

        return $nomenclatureOperation;
    }
}
