<?php

namespace App\Services;

use App\Models\RawMaterial;

class RawMaterialService
{
    public function store(array $data): RawMaterial
    {
        return \DB::transaction(function () use ($data) {
            $rawMaterial = RawMaterial::create($data);

            if ($data['prepaid_amount']) {
                $rawMaterial->payments()->create([
                    'amount' => $data['prepaid_amount'],
                    'comment' => 'первоначальный взнос'
                ]);
            }

            return $rawMaterial;
        });
    }

    public function update(int $id, array $data): RawMaterial
    {
        $material = RawMaterial::findOrFail($id);

        $material->update($data);

        return $material;
    }

    public function delete(int $id): bool
    {
        return RawMaterial::with('payments')
            ->findOrFail($id)
            ->delete();
    }
}
