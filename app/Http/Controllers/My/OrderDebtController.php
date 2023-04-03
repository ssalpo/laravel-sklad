<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDebtRequest;
use App\Models\Client;
use App\Models\ClientDebt;
use App\Models\Order;
use App\Services\ClientDebtService;
use App\Services\Toast;
use Illuminate\Http\Request;

class OrderDebtController extends Controller
{
    public function __construct(
        public ClientDebtService $clientDebtService
    )
    {
    }

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
        $this->clientDebtService
            ->setRelatedToMe(true)
            ->store($request->validated() + ['order_id' => $order]);

        Toast::success(sprintf('Новый долг по заявке №%s успешно добавлен!', $order));

        return to_route('my.orders.index');
    }
}
