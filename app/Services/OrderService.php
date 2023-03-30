<?php

namespace App\Services;

use App\Models\Nomenclature;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function store(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $nomenclatures = Nomenclature::whereIn(
                'id',
                Arr::pluck($data['orderItems'], 'nomenclature_id')
            )->saleType()->get();

            $totals = $this->calculateTotals($data, $nomenclatures);

            $order = Order::create(array_merge(
                [
                    'status' => Order::STATUS_NEW,
                    'profit' => $totals['profit'],
                    'amount' => $totals['amount'],
                ],
                Arr::except($data, 'orderItems')
            ));

            foreach ($data['orderItems'] as $item) {
                $nomenclature = $nomenclatures->where('id', $item['nomenclature_id'])->first();

                $item['price'] = $nomenclature->price;
                $item['price_for_sale'] = $nomenclature->price_for_sale;
                $item['unit'] = $nomenclature->unit;

                $order->orderItems()->create($item);
            }

            return $order;
        });
    }

    public function calculateTotals(array $data, Collection $nomenclatures)
    {
        $amount = 0;
        $profit = 0;

        foreach ($data['orderItems'] as $item) {
            $nomenclature = $nomenclatures->where('id', $item['nomenclature_id'])->first();

            if (!$nomenclature) {
                continue;
            }

            $amount += $nomenclature->price_for_sale * (int)$item['quantity'];
            $profit += !$nomenclature->price ? 0 : ($nomenclature->price_for_sale - $nomenclature->price) * (int)$item['quantity'];

        }

        return compact('amount', 'profit');
    }
}
