<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'client'])->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'user' => $model->user->name,
                'client' => $model->client->name,
                'amount' => $model->amount,
                'profit' => $model->profit,
                'status' => $model->status,
            ]);

        return inertia('Orders/Index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'client']);

        $orderItems = OrderItem::with(['nomenclature'])
            ->whereOrderId($order->id)
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'price_for_sale' => $model->price_for_sale,
                'quantity' => $model->quantity,
                'unit' => $model->unit,
            ]);

        return inertia('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'user' => $order->user->name,
                'client' => $order->client->name,
                'amount' => $order->amount,
                'status' => $order->status,
            ],
            'orderItems' => $orderItems
        ]);
    }
}