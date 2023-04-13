<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\ClientDebt;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
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


        return DB::transaction(function () use ($order, $data) {
            /** @var ClientDebt $debt */
            $debt = $order->debt()->create($data + [
                    'client_id' => $order->client_id,
                    'created_by' => auth()->id()
                ]);

            $this->cashTransaction($debt);

            return $debt;
        });
    }

    public function update(int $id, array $data): ClientDebt
    {
        $debt = ClientDebt::whereClientId($data['client_id'])->findOrFail($id);

        return DB::transaction(function () use ($debt, $data) {
            $debt->update(Arr::only($data, ['amount', 'comment']));

            // При изменении цены так же обновляем сумму в кассе
            $this->cashTransaction($debt);

            return $debt;
        });
    }

    public function destroy(int $clientId, int $id): ClientDebt
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

    public function cashTransaction(ClientDebt $clientDebt): Model|CashTransaction
    {
        $comment = sprintf(
            'Долг по заявке №%s на сумму %s сом.',
            $clientDebt->order_id,
            $clientDebt->amount
        );

        return $clientDebt->cashTransaction()->updateOrCreate(['client_debt_id' => $clientDebt->id], [
            'type' => CashTransaction::TYPE_CREDIT,
            'amount' => $clientDebt->amount,
            'comment' => $comment
        ]);
    }
}
