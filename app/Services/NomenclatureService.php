<?php

namespace App\Services;

use App\Models\Nomenclature;

class NomenclatureService
{
    public function store(array $data)
    {
        $data['price_for_sale'] = $data['price'] + $data['markup'];

        return Nomenclature::create($data);
    }

    public function update(int $id, array $data)
    {
        $nomenclature = Nomenclature::findOrFail($id);

        $data['price_for_sale'] = $data['price'] + $data['markup'];

        $nomenclature->update($data);

        return $nomenclature;
    }
}
