<?php

namespace App\Services;

use App\Models\Nomenclature;
use App\Models\NomenclatureArrival;
use App\Models\NomenclatureOperation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

class LegacyWarehouseStockService
{
    /** Migration baseline: current UI algorithm plus physical customer returns. */
    public function getBalances(): Collection
    {
        $arrivals = NomenclatureArrival::query()
            ->selectRaw('nomenclature_id, COALESCE(SUM(quantity), 0) AS quantity')
            ->groupBy('nomenclature_id')
            ->pluck('quantity', 'nomenclature_id');

        $withdrawals = NomenclatureOperation::query()
            ->typeWithdraw()
            ->selectRaw('nomenclature_id, COALESCE(SUM(quantity), 0) AS quantity')
            ->groupBy('nomenclature_id')
            ->pluck('quantity', 'nomenclature_id');

        $returns = NomenclatureOperation::query()
            ->typeRefund()
            ->selectRaw('nomenclature_id, COALESCE(SUM(quantity), 0) AS quantity')
            ->groupBy('nomenclature_id')
            ->pluck('quantity', 'nomenclature_id');

        $sales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', Order::STATUS_SEND)
            ->selectRaw('order_items.nomenclature_id, COALESCE(SUM(order_items.quantity), 0) AS quantity')
            ->groupBy('order_items.nomenclature_id')
            ->pluck('quantity', 'nomenclature_id');

        return Nomenclature::query()->pluck('id')->mapWithKeys(function ($id) use ($arrivals, $withdrawals, $sales, $returns) {
            $balance = bcsub((string) ($arrivals[$id] ?? 0), (string) ($sales[$id] ?? 0), 6);
            $balance = bcsub($balance, (string) ($withdrawals[$id] ?? 0), 6);
            $balance = bcadd($balance, (string) ($returns[$id] ?? 0), 6);

            return [$id => $balance];
        });
    }
}
