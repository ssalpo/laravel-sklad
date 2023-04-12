<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\NomenclatureOperation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NomenclatureOperationService;
use App\Services\OrderService;
use App\Services\TelegramNotificationService;
use App\Services\Toast;
use App\Services\UnitConvertor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orderService,
        public TelegramNotificationService $telegramNotificationService
    )
    {
    }

    public function index(): Response|ResponseFactory
    {
        $filterParams = request()?->collect()->only(['id', 'client'])->all();

        $orders = Order::with(['user', 'client'])
            ->my()
            ->filter($filterParams)
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->withQueryString()
            ->through(fn($m) => [
                'id' => $m->id,
                'client_id' => $m->client->id,
                'client_name' => $m->client->name,
                'amount' => $m->amount,
                'status' => $m->status,
                'send_at' => $m->send_at?->format('d-m-Y H:i'),
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('My/Orders/Index', compact('orders', 'filterParams'));
    }

    public function show(int $id): Response|ResponseFactory
    {
        $order = Order::with(['user', 'client'])
            ->my()
            ->findOrFail($id);

        $orderItems = OrderItem::with(['nomenclature'])
            ->whereOrderId($order->id)
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'nomenclature_id' => $model->nomenclature->id,
                'nomenclature_name' => $model->nomenclature->name,
                'price_for_sale' => $model->price_for_sale,
                'quantity' => $model->quantity,
                'unit' => $model->unit,
            ]);

        $orderTotalRefunds = (new NomenclatureOperationService)->getTotalOrderRefunds($order->id)
            ->keyBy('nomenclature_id')
            ->toArray();

        $orderRefunds = NomenclatureOperation::typeRefund()
            ->with('nomenclature')
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

        return inertia('My/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'user' => $order->user->name,
                'client' => $order->client->name,
                'amount' => $order->amount,
                'status' => $order->status,
            ],
            'orderItems' => $orderItems,
            'orderTotalRefunds' => $orderTotalRefunds,
            'orderRefunds' => $orderRefunds,
        ]);
    }

    public function create(): Response|ResponseFactory
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

        return inertia('My/Orders/Edit', compact('clients', 'nomenclatures', 'selectedClientId'));
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        $order = $this->orderService->store($request->validated());

        Toast::success('Заявка успешно создана!');

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->newOrder($order);

        return to_route('my.orders.show', $order->id);
    }

    public function markAsSend(int $orderId): RedirectResponse
    {
        $this->orderService
            ->setRelatedToMe()
            ->markAsSend($orderId);

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->orderStatusChanged($orderId, Order::STATUS_SEND);

        Toast::success('Статус заявки изменен на "Отправлено".');

        return back();
    }

    public function markAsCancel(int $orderId): RedirectResponse
    {
        $this->orderService
            ->setRelatedToMe()
            ->markAsCancel($orderId);

        $this->telegramNotificationService
            ->forSubscribedUsers()
            ->orderStatusChanged($orderId, Order::STATUS_CANCELED);

        Toast::success('Статус заявки изменен на "Отменен".');

        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->orderService
            ->setRelatedToMe()
            ->destroy($id);

        Toast::success('Заявка успешно удалена!');

        return to_route('my.orders.index');
    }
}
