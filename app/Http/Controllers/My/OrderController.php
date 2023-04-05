<?php

namespace App\Http\Controllers\My;

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
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Order::with(['user', 'client'])
            ->withSum('debts', 'amount')
            ->my()
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->through(fn($m) => [
                'id' => $m->id,
                'client_id' => $m->client->id,
                'client_name' => $m->client->name,
                'debts_sum_amount' => $m->debts_sum_amount,
                'amount' => $m->amount,
                'status' => $m->status,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
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
                'status' => $order->status,
            ],
            'orderItems' => $orderItems
        ]);
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

        $nomenclatures = Nomenclature::saleType()->get(['id', 'name', 'price_for_sale']);

        return inertia('My/Orders/Edit', compact('clients', 'nomenclatures', 'selectedClientId'));
    }

    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request->validated());

        Toast::success('Заявка успешно создана!');

        return to_route('my.orders.show', $order->id);
    }

    public function destroy(int $id)
    {
        $this->orderService
            ->setRelatedToMe(true)
            ->destroy($id);

        Toast::success('Заявка успешно удалена!');

        return to_route('my.orders.index');
    }
}
