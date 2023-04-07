<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\Services\Toast;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orderService
    )
    {
    }

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

    public function create()
    {
        $clients = Client::with('discounts')
            ->get()
            ->transform(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'discounts' => $m->discounts->pluck('discount', 'nomenclature_id')
            ]);

        $selectedClientId = request('clientId');

        $nomenclatures = Nomenclature::saleType()
            ->hasPriceForSale()
            ->get(['id', 'name', 'price_for_sale']);

        return inertia('Orders/Edit', compact('clients', 'nomenclatures', 'selectedClientId'));
    }

    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request->validated());

        Toast::success('Заявка успешно создана!');

        return to_route('orders.show', $order->id);
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

    public function invoices()
    {
        $orderIds = explode(',', request('ids'));

        $orders = Order::with(['user', 'client', 'orderItems.nomenclature'])
            ->whereIn('id', $orderIds)
            ->get()
            ->transform(fn($m) => [
                'id' => $m->id,
                'user' => $m->user->name,
                'client' => $m->client->name,
                'amount' => $m->amount,
                'status' => $m->status,
                'orderItems' => $m->orderItems->transform(fn($oI) => [
                    'id' => $oI->id,
                    'nomenclature' => $oI->nomenclature->name,
                    'price_for_sale' => $oI->price_for_sale,
                    'quantity' => $oI->quantity,
                    'unit' => $oI->unit,
                ])
            ]);

        return inertia('Orders/Invoices', compact('orders'));
    }

    public function toggleStatus(Order $order, Request $request)
    {
        if (in_array($request->status, array_keys(Order::STATUS_LABELS))) {
            $order->update($request->only('status'));

            Toast::success(sprintf('Статус заказа изменен на "%s"', Order::STATUS_LABELS[$request->status]));
        }

        return back();
    }
}
