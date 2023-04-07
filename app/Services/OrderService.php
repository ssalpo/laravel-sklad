<?php

namespace App\Services;

use App\Models\ClientDiscount;
use App\Models\Nomenclature;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseService
{
    protected bool $relatedToMe = false;

    public function setRelatedToMe(bool $relatedToMe = true): static
    {
        $this->relatedToMe = $relatedToMe;

        return $this;
    }

    public function store(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $nomenclatures = Nomenclature::whereIn(
                'id',
                Arr::pluck($data['orderItems'], 'nomenclature_id')
            )->saleType()->get();

            $clientDiscounts = ClientDiscount::whereClientId($data['client_id'])
                ->whereIn('nomenclature_id', $nomenclatures->pluck('id'))
                ->pluck('discount', 'nomenclature_id');

            $totals = $this->calculateTotals($data, $nomenclatures, $clientDiscounts);

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
                $discount = Arr::get($clientDiscounts, $item['nomenclature_id'], 0);

                $item['price'] = $nomenclature->price;
                $item['price_for_sale'] = $item['price_for_sale'] - $discount;
                $item['unit'] = $nomenclature->unit;
                $item['discount'] = $discount;

                $order->orderItems()->create($item);
            }

            return $order;
        });
    }

    public function calculateTotals(array $data, ModelCollection $nomenclatures, Collection $clientDiscounts): array
    {
        $amount = 0;
        $profit = 0;

        foreach ($data['orderItems'] as $item) {
            $nomenclature = $nomenclatures->where('id', $item['nomenclature_id'])->first();

            $priceForSale = $item['price_for_sale'] - Arr::get($clientDiscounts, $item['nomenclature_id'], 0);

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
        $order = Order::when($this->relatedToMe, fn($o) => $o->relatedToMe())->findOrFail($id);

        if (array_key_exists($status, Order::STATUS_LABELS)) {
            return $order->update(['status' => $status]);
        }

        return false;
    }

    public function markAsSend(int $orderId): bool
    {
        $order = Order::when($this->relatedToMe, static fn($o) => $o->relatedToMe())->findOrFail($orderId);

        if ($order->status === Order::STATUS_NEW) {
            return $order->update(['status' => Order::STATUS_SEND]);
        }

        return false;
    }

    public function markAsCancel(int $orderId): bool
    {
        $order = Order::when($this->relatedToMe, static fn($o) => $o->relatedToMe())->findOrFail($orderId);

        if ($order->status === Order::STATUS_SEND) {
            return $order->update(['status' => Order::STATUS_CANCELED]);
        }

        return false;
    }
}
