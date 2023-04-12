<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\NomenclatureOperation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\ClientDebtService;
use App\Services\NomenclatureOperationService;
use App\Services\OrderService;
use App\Services\TelegramNotificationService;
use App\Services\Toast;
use App\Services\UnitConvertor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orderService,
        public ClientDebtService $clientDebtService,
        public TelegramNotificationService $telegramNotificationService
    )
    {
    }

    public function index(): Response
    {
        $filterParams = request()?->collect()->except(['page'])->all();

        $orders = Order::with(['user', 'client', 'debt'])
            ->filter($filterParams)
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->withQueryString()
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
                'has_debt' => !is_null($m->debt),
                'send_at' => $m->send_at?->format('d-m-Y H:i'),
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        $orderDebtAmounts = $this->clientDebtService->getOrderDebts($orders->pluck('id')->toArray());

        return inertia('Orders/Index', compact('orders', 'filterParams', 'orderDebtAmounts'));
    }

    public function create(): Response
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

    public function store(OrderRequest $request): RedirectResponse
    {
        $order = $this->orderService->store($request->validated());

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->newOrder($order);

        Toast::success('Заявка успешно создана!');

        return to_route('orders.show', $order->id);
    }

    public function show(Order $order): Response
    {
        $order->load(['user', 'client']);

        $orderItems = OrderItem::with(['nomenclature'])
            ->whereOrderId($order->id)
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'nomenclature_id' => $model->nomenclature->id,
                'nomenclature_name' => $model->nomenclature->name,
                'price' => $model->price,
                'price_for_sale' => $model->price_for_sale,
                'quantity' => $model->quantity,
                'unit' => $model->unit
            ]);

        $orderTotalRefunds = (new NomenclatureOperationService)->getTotalOrderRefunds($order->id)
            ->keyBy('nomenclature_id')
            ->toArray();

        $orderRefunds = NomenclatureOperation::typeRefund()
            ->with('nomenclature', 'debt')
            ->whereOrderId($order->id)
            ->get()
            ->transform(fn($m) => [
                'nomenclature' => $m->nomenclature->name,
                'nomenclature_unit' => UnitConvertor::UNIT_LABELS[$m->nomenclature->unit],
                'quantity' => $m->quantity,
                'price' => $m->price,
                'price_for_sale' => $m->price_for_sale,
                'comment' => $m->comment,
            ]);

        return inertia('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'user' => $order->user->name,
                'client_id' => $order->client->id,
                'client' => $order->client->name,
                'has_debt' => !is_null($order->debt),
                'amount' => number_format($order->amount, 2, '.', ''),
                'status' => $order->status,
            ],
            'orderItems' => $orderItems,
            'orderTotalRefunds' => $orderTotalRefunds,
            'orderRefunds' => $orderRefunds,
        ]);
    }

    public function invoices(): Response
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

    public function markAsSend(int $orderId, Request $request): RedirectResponse
    {
        $this->orderService->markAsSend($orderId, $request->rollback === true);

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->orderStatusChanged($orderId, Order::STATUS_SEND);

        Toast::success('Статус заявки изменен на "Отправлено".');

        return back();
    }

    public function markAsCancel(int $orderId): RedirectResponse
    {
        $this->orderService->markAsCancel($orderId);

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->orderStatusChanged($orderId, Order::STATUS_CANCELED);

        Toast::success('Статус заявки изменен на "Отменен".');

        return back();
    }

    public function toggleStatus(int $order, Request $request): RedirectResponse
    {
        $this->orderService->toggleStatus($order, $request->status);

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->orderStatusChanged($order, $request->status);

        Toast::success(sprintf('Статус заявки изменен на "%s"', Order::STATUS_LABELS[$request->status]));

        return back();
    }
}
