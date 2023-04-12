<?php

namespace App\Services;

use App\Models\Nomenclature;
use Illuminate\Http\Request;

class NomenclatureService
{
    public function store(array $data)
    {
        return Nomenclature::create($data);
    }

    public function update(int $id, array $data)
    {
        $nomenclature = Nomenclature::findOrFail($id);

        $nomenclature->update($data);

        return $nomenclature;
    }

    public function changeMarkups(float $dollarExchangeRate)
    {
        $nomenclatures = Nomenclature::priceNotManual()
            ->saleType()
            ->with('mixtureComposition.mixtureCompositionItems')
            ->get();

        foreach ($nomenclatures as $nomenclature) {
            if (!$nomenclature->mixtureComposition) {
                continue;
            }

            $totalSum = (new MixtureCompositionService)->calculateTotalSum($nomenclature->mixtureComposition);

            $price = $totalSum * $dollarExchangeRate;

            $nomenclature->update([
                'price' => round($price, 2, PHP_ROUND_HALF_UP),
                'price_for_sale' => round($price + $nomenclature->markup, 2, PHP_ROUND_HALF_UP),
                'dollar_exchange_rate' => $dollarExchangeRate
            ]);
        }
    }

    public static function mergeNomenclaturePrices(int $id, array $data): array
    {
        $nomenclature = Nomenclature::findOrFail($id);

        return array_merge($data, ['price' => $nomenclature->price, 'price_for_sale' => $nomenclature->price_for_sale]);
    }
}
