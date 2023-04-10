<?php

namespace App\Services;

use App\Models\NomenclatureOperation;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NomenclatureOperationService extends BaseService
{
    private $relatedToMe = false;

    public function store(array $data)
    {
        return NomenclatureOperation::create(
            NomenclatureService::mergeNomenclaturePrices(
                $data['nomenclature_id'],
                $data
            )
        );
    }

    public function update(int $id, array $data)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if ($nomenclatureOperation->can_edit) {
            $nomenclatureOperation->update(
                NomenclatureService::mergeNomenclaturePrices(
                    $data['nomenclature_id'],
                    $data
                )
            );
        }

        return $nomenclatureOperation;
    }

    public function delete(int $id)
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if ($nomenclatureOperation->can_edit) {
            $nomenclatureOperation->delete();
        }

        return $nomenclatureOperation;
    }

    public function refundOrder(array $data)
    {
        $orderItem = OrderItem::whereOrderId($data['order_id'])
            ->whereHas(
                'order', fn($q) => $q->statusSend()
                ->when($this->relatedToMe, fn($q) => $q->my())
            )
            ->whereNomenclatureId($data['nomenclature_id'])
            ->where('quantity', '>=', $data['quantity'])
            ->findOrFail($data['order_item_id']);


        return DB::transaction(function () use ($data, $orderItem) {
            $order = $orderItem->order;

            $order->update([
                'amount' => $order->amount - ($data['quantity'] * $orderItem->price_for_sale),
                'profit' => $order->profit - ($data['quantity'] * ($orderItem->price_for_sale - $orderItem->price))
            ]);

            return NomenclatureOperation::create(array_merge(
                $data,
                [
                    'type' => NomenclatureOperation::OPERATION_TYPE_REFUND,
                    'price' => $orderItem->price,
                    'price_for_sale' => $orderItem->price_for_sale,
                ]
            ));
        });

    }

    public function getTotalOrderRefunds(int $orderId): Collection
    {
        return NomenclatureOperation::select(
            'nomenclature_id',
            DB::raw('SUM(quantity) AS quantity'),
            DB::raw('SUM(price_for_sale * quantity) AS amount'),
        )
            ->whereOrderId($orderId)
            ->groupBy('nomenclature_id')
            ->get();
    }

    public function setRelatedToMe()
    {
        $this->relatedToMe = true;

        return $this;
    }
}
