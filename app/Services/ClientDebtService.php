<?php

namespace App\Services;

use App\Models\ClientDebt;
use App\Models\ClientDebtPayment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ClientDebtService extends BaseService
{
    protected bool $isRelatedToMe = false;

    public function setRelatedToMe(bool $isRelatedToMe): self
    {
        $this->isRelatedToMe = $isRelatedToMe;

        return $this;
    }

    public function store(array $data)
    {
        $order = Order::relatedToMe($this->isRelatedToMe)
            ->whereDoesntHave('debt')
            ->findOrFail($data['order_id']);

        return $order->debt()->create($data + [
                'client_id' => $order->client_id,
                'created_by' => auth()->id()
            ]);
    }

    public function update(int $id, array $data)
    {
        $debt = ClientDebt::whereClientId($data['client_id'])->findOrFail($id);

        $debt->update(Arr::only($data, ['amount', 'comment']));

        return $debt;
    }

    public function destroy(int $clientId, int $id)
    {
        $debt = ClientDebt::whereClientId($clientId)->findOrFail($id);

        $debt->delete();

        return $debt;
    }

    public function getOrderDebts(array|int $id): array
    {
        $ids = !is_array($id) ? (array)$id : $id;


        return ClientDebt::whereIn('order_id', $ids)
            ->withSum('payments', 'amount')
            ->get()
            ->transform(fn($m) => [
                'order_id' => $m->order_id,
                'amountDebts' => $m->amount - $m->payments_sum_amount,
            ])
            ->pluck('amountDebts', 'order_id')
            ->toArray();
    }
}
