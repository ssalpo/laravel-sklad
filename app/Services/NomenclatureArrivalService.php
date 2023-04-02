<?php

namespace App\Services;

use App\Models\NomenclatureArrival;

class NomenclatureArrivalService
{
    public function store(array $data)
    {
        return NomenclatureArrival::create($data);
    }

    public function update(int $id, array $data)
    {
        $nomenclatureArrival = NomenclatureArrival::findOrFail($id);

        if($nomenclatureArrival->can_edit) {
            $nomenclatureArrival->update($data);
        }

        return $nomenclatureArrival;
    }
}
