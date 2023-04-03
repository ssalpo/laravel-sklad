<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class MixtureCompositionService
{
    public function calculateTotalSum(Model $mixtureComposition)
    {
        $weight = $mixtureComposition->weight; // UnitConvertor::toKg($mixtureComposition->weight, $mixtureComposition->weight_unit);

        $endResultPrice = $mixtureComposition->mixtureCompositionItems->where('end_result', true)->sum('price') + $mixtureComposition->worker_price;

        $mixtureCompositionItemsTotalAmount = $mixtureComposition->mixtureCompositionItems->where('end_result', false)->sum('price');

        $totalSum = ($mixtureCompositionItemsTotalAmount / $mixtureComposition->water) * $weight;

        $totalSum += $endResultPrice;

        return round($totalSum, 3, PHP_ROUND_HALF_UP);
    }
}
