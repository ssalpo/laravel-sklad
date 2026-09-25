<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\NomenclatureOperation;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\WarehouseMovement;
use App\Enums\WarehouseMovementType;
use App\Enums\WarehouseMovementDirection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NomenclatureOperationService extends BaseService
{
    private bool $relatedToMe = false;

    public function __construct(private WarehouseMovementService $warehouseMovements)
    {
    }

    public function store(array $data): NomenclatureOperation|WarehouseMovement
    {
        if (config('warehouse.use_movements')) {
            return DB::transaction(function () use ($data) {
                $movement = $this->warehouseMovements->expense(array_merge(
                    NomenclatureService::mergeNomenclaturePrices($data['nomenclature_id'], $data),
                    ['type' => WarehouseMovementType::WRITE_OFF]
                ));
                $movement->cashTransaction()->create($this->getCashTransactionData($movement));

                return $movement;
            });
        }

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

    public function update(int $id, array $data): NomenclatureOperation|WarehouseMovement
    {
        if (config('warehouse.use_movements')) {
            return DB::transaction(function () use ($id, $data) {
                $movement = WarehouseMovement::query()->where('type', WarehouseMovementType::WRITE_OFF)->findOrFail($id);
                $replacement = $this->warehouseMovements->correct($movement, array_merge(
                    NomenclatureService::mergeNomenclaturePrices($data['nomenclature_id'], $data),
                    ['type' => WarehouseMovementType::WRITE_OFF]
                ));
                $movement->cashTransaction?->cancel();
                $replacement->cashTransaction()->create($this->getCashTransactionData($replacement));

                return $replacement;
            });
        }

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

    public function delete(int $id): NomenclatureOperation|WarehouseMovement
    {
        if (config('warehouse.use_movements')) {
            return DB::transaction(function () use ($id) {
                // В списаниях идентификатором является ID движения. На странице
                // возвратов могут остаться ID legacy-операций, поэтому сначала
                // проверяем оба вида записей.
                $movement = WarehouseMovement::query()
                    ->where('type', WarehouseMovementType::WRITE_OFF)
                    ->find($id);

                if ($movement) {
                    $reversal = $this->warehouseMovements->reverse($movement, 'Отмена ручного списания');
                    $movement->cashTransaction?->cancel();

                    return $reversal;
                }

                $returnMovement = WarehouseMovement::query()
                    ->where('type', WarehouseMovementType::CUSTOMER_RETURN)
                    ->find($id);

                if ($returnMovement) {
                    return $this->deleteWarehouseReturn($returnMovement);
                }

                $legacyReturn = NomenclatureOperation::query()
                    ->typeRefund()
                    ->with(['order', 'orderItem'])
                    ->findOrFail($id);

                if (!$legacyReturn->can_edit) {
                    return $legacyReturn;
                }

                $returnMovement = WarehouseMovement::withTrashed()
                    ->where('type', WarehouseMovementType::CUSTOMER_RETURN)
                    ->where('legacy_source_type', 'nomenclature_operation')
                    ->where('legacy_source_id', $legacyReturn->id)
                    ->first();

                if ($returnMovement && !$returnMovement->trashed()) {
                    return $this->deleteWarehouseReturn($returnMovement, $legacyReturn);
                }

                // Если перенос ещё не запускался для этой исторической записи,
                // сохраняем прежнее поведение удаления возврата.
                $this->restoreOrderFinancials($legacyReturn->order, $legacyReturn->orderItem, $legacyReturn->quantity);
                $legacyReturn->delete();

                return $legacyReturn;
            });
        }

        $nomenclatureOperation = NomenclatureOperation::with(['order', 'orderItem'])->findOrFail($id);

        if ($nomenclatureOperation->can_edit) {
            DB::transaction(static function () use ($nomenclatureOperation) {
                if ($nomenclatureOperation->type === NomenclatureOperation::OPERATION_TYPE_WITHDRAW) {
                    $nomenclatureOperation->cashTransaction?->cancel();
                }

                if ($nomenclatureOperation->type === NomenclatureOperation::OPERATION_TYPE_REFUND) {
                    $nomenclatureOperation->order->update([
                        'amount' => $nomenclatureOperation->order->amount + ($nomenclatureOperation->quantity * $nomenclatureOperation->orderItem->price_for_sale),
                        'profit' => $nomenclatureOperation->order->profit + ($nomenclatureOperation->quantity * ($nomenclatureOperation->orderItem->price_for_sale - $nomenclatureOperation->orderItem->price))
                    ]);
                }

                $nomenclatureOperation->delete();
            });
        }

        return $nomenclatureOperation;
    }

    /**
     * Удаляет возврат без обратного движения: запись перестаёт участвовать в
     * остатке, а сумма и прибыль заказа возвращаются к состоянию до возврата.
     */
    private function deleteWarehouseReturn(
        WarehouseMovement $movement,
        ?NomenclatureOperation $legacyReturn = null
    ): WarehouseMovement {
        $movement = WarehouseMovement::query()->lockForUpdate()->findOrFail($movement->id);
        $orderId = $movement->order_id ?? $legacyReturn?->order_id;
        $orderItemId = $movement->order_item_id ?? $legacyReturn?->order_item_id;
        $order = $orderId ? Order::query()->lockForUpdate()->find($orderId) : null;
        $orderItem = $orderItemId ? OrderItem::query()->lockForUpdate()->find($orderItemId) : null;

        $this->restoreOrderFinancials($order, $orderItem, $movement->quantity);
        $movement->delete();

        if ($legacyReturn && !$legacyReturn->trashed()) {
            $legacyReturn->delete();
        }

        return $movement;
    }

    private function restoreOrderFinancials(?Order $order, ?OrderItem $orderItem, $quantity): void
    {
        if (!$order || !$orderItem) {
            return;
        }

        $order->update([
            'amount' => $order->amount + ($quantity * $orderItem->price_for_sale),
            'profit' => $order->profit + ($quantity * ($orderItem->price_for_sale - $orderItem->price)),
        ]);
    }

    public function refundOrder(array $data)
    {
        if (config('warehouse.use_movements')) {
            return $this->refundWarehouseOrder($data);
        }

        $orderItem = OrderItem::whereOrderId($data['order_id'])
            ->whereHas(
                'order', fn($q) => $q->statusSend()
                ->whereDoesntHave('debt')
                ->whereDoesntHave('cashTransaction')
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

    public function getTotalOrderRefunds(int $orderId)
    {
        if (config('warehouse.use_movements')) {
            $legacy = NomenclatureOperation::select(
                'nomenclature_id',
                DB::raw('SUM(quantity) AS quantity'),
                DB::raw('SUM(price_for_sale * quantity) AS amount'),
            )
                ->whereOrderId($orderId)
                ->typeRefund()
                ->whereNotIn('id', $this->migratedLegacyReturnIds())
                ->groupBy('nomenclature_id')
                ->get();
            $current = WarehouseMovement::select(
                'nomenclature_id', DB::raw('SUM(quantity) AS quantity'), DB::raw('SUM(price_for_sale * quantity) AS amount'),
            )
                ->whereOrderId($orderId)
                ->where('type', WarehouseMovementType::CUSTOMER_RETURN)
                ->groupBy('nomenclature_id')
                ->get();

            return $legacy->concat($current)->groupBy('nomenclature_id')->map(function ($items, $nomenclatureId) {
                return (object) [
                    'nomenclature_id' => $nomenclatureId,
                    'quantity' => $items->sum('quantity'),
                    'amount' => $items->sum('amount'),
                ];
            })->values();
        }

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

    public function getOrderRefunds(int $orderId)
    {
        if (config('warehouse.use_movements')) {
            $legacy = NomenclatureOperation::typeRefund()
                ->with('nomenclature')
                ->whereOrderId($orderId)
                ->whereNotIn('id', $this->migratedLegacyReturnIds())
                ->get();
            $current = WarehouseMovement::query()->whereOrderId($orderId)
                ->where('type', WarehouseMovementType::CUSTOMER_RETURN)
                ->with('nomenclature')->get();

            return $legacy->concat($current);
        }

        return NomenclatureOperation::typeRefund()->with('nomenclature')->whereOrderId($orderId)->get();
    }

    private function migratedLegacyReturnIds()
    {
        return WarehouseMovement::query()
            ->select('legacy_source_id')
            ->where('type', WarehouseMovementType::CUSTOMER_RETURN)
            ->where('legacy_source_type', 'nomenclature_operation')
            ->whereNotNull('legacy_source_id');
    }

    private function refundWarehouseOrder(array $data): WarehouseMovement
    {
        return DB::transaction(function () use ($data) {
            $orderItem = OrderItem::query()->lockForUpdate()->whereOrderId($data['order_id'])
                ->whereNomenclatureId($data['nomenclature_id'])
                ->whereHas('order', fn($q) => $q->statusSend()->whereDoesntHave('debt')->whereDoesntHave('cashTransaction')->when($this->relatedToMe, fn($q) => $q->my()))
                ->findOrFail($data['order_item_id']);
            $order = $orderItem->order;
            $legacyRefunded = NomenclatureOperation::query()->typeRefund()->whereOrderItemId($orderItem->id)->sum('quantity');
            $movementRefunded = WarehouseMovement::query()->whereOrderItemId($orderItem->id)
                ->where('type', WarehouseMovementType::CUSTOMER_RETURN)->sum('quantity');
            $available = bcsub((string) $orderItem->quantity, bcadd((string) $legacyRefunded, (string) $movementRefunded, 6), 6);

            if (bccomp((string) $data['quantity'], $available, 6) === 1) {
                throw \Illuminate\Validation\ValidationException::withMessages(['quantity' => 'Количество возврата превышает количество проданного товара.']);
            }

            $order->update([
                'amount' => $order->amount - ($data['quantity'] * $orderItem->price_for_sale),
                'profit' => $order->profit - ($data['quantity'] * ($orderItem->price_for_sale - $orderItem->price)),
            ]);

            return $this->warehouseMovements->income([
                'nomenclature_id' => $orderItem->nomenclature_id,
                'type' => WarehouseMovementType::CUSTOMER_RETURN,
                'quantity' => $data['quantity'],
                'price' => $orderItem->price,
                'price_for_sale' => $orderItem->price_for_sale,
                'source_type' => Order::class,
                'source_id' => $order->id,
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'comment' => $data['comment'],
                'occurred_at' => now(),
            ]);
        });
    }

    public function setRelatedToMe(): static
    {
        $this->relatedToMe = true;

        return $this;
    }
}
