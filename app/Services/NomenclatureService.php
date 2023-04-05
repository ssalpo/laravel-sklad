<?php

namespace App\Services;

use App\Models\Nomenclature;
use Illuminate\Http\Request;

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

    public function changeMarkups()
    {
        $nomenclatures = Nomenclature::with('mixtureComposition.mixtureCompositionItems')
            ->saleType()
            ->get();

        foreach ($nomenclatures as $nomenclature) {
            if (!$nomenclature->mixtureComposition) {
                continue;
            }

            $totalSum = (new MixtureCompositionService)->calculateTotalSum($nomenclature->mixtureComposition);

            if ($nomenclature->dollar_exchange_rate) {
                $price = $totalSum * $nomenclature->dollar_exchange_rate;

                $nomenclature->update([
                    'price' => round($price, 2, PHP_ROUND_HALF_UP),
                    'price_for_sale' => round($price + $nomenclature->markup, 2, PHP_ROUND_HALF_UP)
                ]);
            }
        }
    }

    public static function mergeNomenclaturePrices(int $id, array $data): array
    {
        $nomenclature = Nomenclature::findOrFail($id);

        return array_merge($data, ['price' => $nomenclature->price, 'price_for_sale' => $nomenclature->price_for_sale]);
    }
}
