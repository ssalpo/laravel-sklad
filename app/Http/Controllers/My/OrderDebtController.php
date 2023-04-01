<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDebtRequest;
use App\Models\Client;
use App\Models\ClientDebt;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderDebtController extends Controller
{
    public function create(int $orderId)
    {
        $order = Order::with(['client'])->my()->findOrFail($orderId);

        return inertia('My/OrderDebt/Edit', [
            'order' => [
                'id' => $order->id,
                'client' => $order->client->name,
            ]
        ]);
    }

    public function store(int $order, OrderDebtRequest $request)
    {
        $order = Order::my()->findOrFail($order);

        $order->debts()->create($request->validated() + [
                'client_id' => $order->client_id,
                'created_by' => auth()->id()
            ]);

        return to_route('my.orders.index');
    }
}
