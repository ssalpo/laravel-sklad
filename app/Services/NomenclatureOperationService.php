<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\NomenclatureOperation;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NomenclatureOperationService extends BaseService
{
    private bool $relatedToMe = false;

    public function store(array $data): NomenclatureOperation
    {
        return DB::transaction(function () use ($data) {
            $nomenclatureOperation = NomenclatureOperation::create(
                NomenclatureService::mergeNomenclaturePrices(
                    $data['nomenclature_id'],
                    $data
                )
            );

            // Записываем в кассу только если была проведена операция списания
            if ($nomenclatureOperation->type === NomenclatureOperation::OPERATION_TYPE_WITHDRAW) {
                $nomenclatureOperation->cashTransaction()->create(
                    $this->getCashTransactionData($nomenclatureOperation)
                );
            }

            return $nomenclatureOperation;
        });
    }

    public function update(int $id, array $data): NomenclatureOperation
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if ($nomenclatureOperation->can_edit) {
            DB::transaction(function () use ($nomenclatureOperation, $data) {
                $nomenclatureOperation->update(
                    NomenclatureService::mergeNomenclaturePrices(
                        $data['nomenclature_id'],
                        $data
                    )
                );

                // Записываем в кассу только если была проведена операция списания
                if ($nomenclatureOperation->type === NomenclatureOperation::OPERATION_TYPE_WITHDRAW) {
                    $nomenclatureOperation->cashTransaction->update(
                        $this->getCashTransactionData($nomenclatureOperation)
                    );
                }
            });
        }

        return $nomenclatureOperation;
    }

    public function delete(int $id): NomenclatureOperation
    {
        $nomenclatureOperation = NomenclatureOperation::findOrFail($id);

        if ($nomenclatureOperation->can_edit) {
            DB::transaction(static function () use ($nomenclatureOperation) {
                if ($nomenclatureOperation->type === NomenclatureOperation::OPERATION_TYPE_WITHDRAW) {
                    $nomenclatureOperation->cashTransaction?->cancel();
                }

                $nomenclatureOperation->delete();
            });
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

    private function getCashTransactionData($nomenclatureOperation): array
    {
        // Списание по номенклатуре №1, кол-во: 2 шт.
        $nomenclature = $nomenclatureOperation->nomenclature;

        return [
            'type' => CashTransaction::TYPE_CREDIT,
            'amount' => $nomenclatureOperation->quantity * $nomenclatureOperation->price,
            'comment' => sprintf(
                'Списание по номенклатуре №%s, кол-во: %s %s',
                $nomenclature->id, $nomenclatureOperation->quantity, UnitConvertor::UNIT_LABELS[$nomenclature->unit]
            )
        ];
    }

    public function setRelatedToMe(): static
    {
        $this->relatedToMe = true;

        return $this;
    }
}
