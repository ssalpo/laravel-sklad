<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Order::with(['user', 'client'])->my()->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'client' => $model->client->name,
                'amount' => $model->amount,
                'status' => $model->status,
            ]);

        return inertia('My/Orders/Index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'client'])
            ->my()
            ->findOrFail($id);

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

        return inertia('My/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'user' => $order->user->name,
                'client' => $order->client->name,
                'amount' => $order->amount,
                'currency_type' => $order->currency_type,
                'status' => $order->status,
            ],
            'orderItems' => $orderItems
        ]);
    }

    public function create()
    {
        $clients = Client::all(['id', 'name']);
        $nomenclatures = Nomenclature::saleType()->get(['id', 'name', 'price_for_sale']);

        return inertia('My/Orders/Edit', compact('clients', 'nomenclatures'));
    }

    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request->validated());

        return to_route('orders.show', $order->id);
    }

    public function destroy(int $id)
    {
        Order::my()
            ->statusNew()
            ->findOrFail($id)
            ->delete();

        return to_route('my.orders.index');
    }
}
