<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\Nomenclature;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WarehouseMovement;
use App\Enums\WarehouseMovementType;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService extends BaseService
{
    protected bool $relatedToMe = false;

    public function __construct(private WarehouseMovementService $warehouseMovements)
    {
    }

    public function setRelatedToMe(bool $relatedToMe = true): static
    {
        $this->relatedToMe = $relatedToMe;

        return $this;
    }

    public function store(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $nomenclatures = Nomenclature::whereIn(
                'id',
                Arr::pluck($data['orderItems'], 'nomenclature_id')
            )->saleType()->get();

            $totals = $this->calculateTotals($data, $nomenclatures);

            $order = Order::create(array_merge(
                [
                    'status' => Order::STATUS_NEW,
                    'profit' => $totals['profit'],
                    'amount' => $totals['amount'],
                ],
                Arr::except($data, 'orderItems')
            ));

            foreach ($data['orderItems'] as $item) {
                $nomenclature = $nomenclatures->where('id', $item['nomenclature_id'])->first();

                $item['price'] = $nomenclature->price;
                $item['price_for_sale'] = $item['price_for_sale'];
                $item['unit'] = $nomenclature->unit;
                $item['discount'] = max($nomenclature->price_for_sale - $item['price_for_sale'], 0);

                $order->orderItems()->create($item);
            }

            return $order;
        });
    }

    public function calculateTotals(array $data, ModelCollection $nomenclatures): array
    {
        $amount = 0;
        $profit = 0;

        foreach ($data['orderItems'] as $item) {
            $nomenclature = $nomenclatures->where('id', $item['nomenclature_id'])->first();

            $priceForSale = $item['price_for_sale'];

            if (!$nomenclature) {
                continue;
            }

            $amount += $priceForSale * (int)$item['quantity'];
            $profit += !$nomenclature->price ? 0 : ($priceForSale - $nomenclature->price) * (int)$item['quantity'];
        }

        return compact('amount', 'profit');
    }

    public function destroy(int $orderId): bool
    {
        return Order::when($this->relatedToMe, static fn($q) => $q->my())
            ->statusNew()
            ->findOrFail($orderId)
            ->delete();
    }

    public function toggleStatus(int $id, int $status): bool
    {
        if (config('warehouse.use_movements')) {
            if ($status === Order::STATUS_SEND) {
                return $this->markAsSend($id);
            }
            if ($status === Order::STATUS_CANCELED) {
                return $this->markAsCancel($id);
            }
        }

        $order = Order::when($this->relatedToMe, fn($o) => $o->relatedToMe())->findOrFail($id);

        if (array_key_exists($status, Order::STATUS_LABELS)) {
            return $order->update(['status' => $status]);
        }

        return false;
    }

    public function markAsSend(int $orderId, bool $isRollback = false): bool
    {
        return DB::transaction(function () use ($orderId, $isRollback) {
            $order = Order::when($this->relatedToMe, static fn($query) => $query->relatedToMe(true))
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status !== Order::STATUS_NEW && !($isRollback && $order->status === Order::STATUS_CANCELED)) {
                return false;
            }

            if ($isRollback) {
                $this->rollbackCashTransaction($order);
            }

            $data = ['status' => Order::STATUS_SEND];
            if (!$isRollback) {
                $data['send_at'] = now();
            }
            $updated = $order->update($data);

            if (config('warehouse.use_movements')) {
                $this->createSales($order->fresh());
            }

            return $updated;
        });
    }

    public function markAsCancel(int $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::when($this->relatedToMe, static fn($query) => $query->relatedToMe(true))
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status !== Order::STATUS_SEND) {
                return false;
            }

            $this->cancelCashTransaction($order);

            if (config('warehouse.use_movements')) {
                $this->deactivateSales($order);
            }

            return $order->update(['status' => Order::STATUS_CANCELED]);
        });
    }

    public function doPayment(int $orderId, float $debtAmount = 0): void
    {
        $order = Order::with('debt')->findOrFail($orderId);

        if ($debtAmount > $order->amount) {
            throw ValidationException::withMessages(['amount' => sprintf('Максимальная сумма для ввода %s сом.', $order->amount)]);
        }

        DB::transaction(function () use ($order, $debtAmount) {
            if (is_null($order->debt) && $debtAmount > 0 && $debtAmount <= $order->amount) {
                $clientDebt = $order->debt()->create([
                    'client_id' => $order->client_id,
                    'amount' => $debtAmount
                ]);
            }

            if(!is_null($order->debt)) {
                $debtAmount = $order->debt->amount;
            }

            if($debtAmount < $order->amount) {
                $this->cashTransaction($order, $debtAmount > 0 ? $order->amount - $debtAmount : $order->amount);
            }
        });
    }


    public function cashTransaction(Order $order, float $amount): Model
    {

        $comment = sprintf(
            'Оплата по заявке №%s на сумму %s сом.',
            $order->id,
            number_format($amount, 2, '.', '')
        );

        return $order->cashTransaction()->updateOrCreate(['order_id' => $order->id], [
            'type' => CashTransaction::TYPE_DEBIT,
            'amount' => $amount,
            'comment' => $comment
        ]);
    }

    private function cancelCashTransaction(Order $order): ?bool
    {
        return $order->cashTransaction?->update(['status' => CashTransaction::STATUS_CANCELED]);
    }

    private function rollbackCashTransaction(Order $order): ?bool
    {
        return $order->cashTransaction?->update(['status' => CashTransaction::STATUS_COMPLETED]);
    }

    private function createSales(Order $order): void
    {
        $items = OrderItem::query()->whereOrderId($order->id)->lockForUpdate()->get();
        foreach ($items as $item) {
            $sale = WarehouseMovement::withTrashed()->whereOrderItemId($item->id)
                ->where('type', WarehouseMovementType::SALE)->orderByDesc('id')->first();

            if ($sale && !$sale->trashed()) {
                continue;
            }

            if ($sale && $sale->trashed()) {
                $sale->restore();
                continue;
            }

            $this->warehouseMovements->expense([
                'nomenclature_id' => $item->nomenclature_id,
                'type' => WarehouseMovementType::SALE,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'price_for_sale' => $item->price_for_sale,
                'source_type' => Order::class,
                'source_id' => $order->id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'comment' => "Продажа по заказу #{$order->id}",
                'occurred_at' => $order->send_at ?? now(),
                'created_by' => $order->user_id,
            ]);
        }
    }

    private function deactivateSales(Order $order): void
    {
        $items = OrderItem::query()->whereOrderId($order->id)->lockForUpdate()->get();
        foreach ($items as $item) {
            $sale = WarehouseMovement::query()->whereOrderItemId($item->id)
                ->where('type', WarehouseMovementType::SALE)->orderByDesc('id')->lockForUpdate()->first();
            if ($sale) {
                $sale->delete();
            }
        }
    }
}
