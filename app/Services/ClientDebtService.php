<?php

namespace App\Services;

use App\Models\ClientDebt;
use App\Models\Order;
use Illuminate\Support\Arr;

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
            ->findOrFail($data['order_id']);

        return $order->debts()->create($data + [
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
}
