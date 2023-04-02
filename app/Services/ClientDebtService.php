<?php

namespace App\Services;

use App\Models\Order;

class ClientDebtService
{
    public function store(int $order, array $data)
    {
        $order = Order::my()->findOrFail($order);

        return $order->debts()->create($data + [
                'client_id' => $order->client_id,
                'created_by' => auth()->id()
            ]);
    }
}
