<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\RawMaterialPayment;
use Illuminate\Validation\ValidationException;

class RawMaterialPaymentService
{
    public function store(array $data): RawMaterialPayment
    {
        $this->validateAmountEquality($data['raw_material_id'], $data['amount']);

        return RawMaterialPayment::create($data);
    }

    public function update(int $id, array $data): RawMaterialPayment
    {
        $material = RawMaterialPayment::findOrFail($id);

        $this->validateAmountEquality($data['raw_material_id'], $data['amount']);

        $material->update($data);

        return $material;

    }

    public function delete(int $id): bool
    {
        return RawMaterialPayment::findOrFail($id)
            ->delete();
    }

    public function validateAmountEquality(int $rawMaterialId, float $amount): void
    {
        $rawMaterial = RawMaterial::withSum('payments', 'amount')->findOrFail($rawMaterialId);

        $endAmount = $rawMaterial->payments_sum_amount + $amount;

        if ($endAmount > $rawMaterial->total_amount) {
            throw ValidationException::withMessages([
                'amount' => 'Максимальная сумма погашения должно быть равно $' . ($rawMaterial->total_amount - $rawMaterial->payments_sum_amount)
            ]);
        }
    }
}
