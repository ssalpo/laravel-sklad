<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'client'])
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->through(fn($m) => [
                'id' => $m->id,
                'user' => $m->user->name,
                'client' => [
                    'id' => $m->client->id,
                    'name' => $m->client->name,
                ],
                'amount' => $m->amount,
                'profit' => $m->profit,
                'status' => $m->status,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
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
                'unit' => $model->unit
            ]);

        return inertia('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'user' => $order->user->name,
                'client' => $order->client->name,
                'amount' => number_format($order->amount, 2, '.', ''),
                'status' => $order->status,
            ],
            'orderItems' => $orderItems
        ]);
    }

    public function invoice(Order $order)
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

        return inertia('Orders/Invoice', [
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

    public function toggleStatus(Order $order, Request $request)
    {
        if (in_array($request->status, array_keys(Order::STATUS_LABELS))) {
            $order->update($request->only('status'));
        }

        return back();
    }
}
