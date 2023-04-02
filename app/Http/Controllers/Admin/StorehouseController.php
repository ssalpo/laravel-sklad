<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NomenclatureArrival;
use App\Models\NomenclatureOperation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class StorehouseController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::select(
            'nomenclature_id',
            DB::raw('SUM(quantity) as quantity')
        )->join('orders', fn($q) => $q->on('orders.id', '=', 'order_items.order_id')->where('orders.status', Order::STATUS_SEND))
            ->groupBy('nomenclature_id')->get();

        $nomenclatureBalances = NomenclatureArrival::select(
            'nomenclature_id',
            DB::raw('n.name as nomenclature_name'),
            DB::raw('n.unit as unit'),
            DB::raw('SUM(quantity) as quantity')
        )->join('nomenclatures as n', 'n.id', '=', 'nomenclature_arrivals.nomenclature_id')
            ->groupBy('nomenclature_id')
            ->get()
            ->transform(function ($m) use ($orderItems) {
                $orderItem = $orderItems->where('nomenclature_id', $m->nomenclature_id)->first();

                return [
                    'id' => $m->nomenclature_id,
                    'name' => $m->nomenclature_name,
                    'quantity' => $m->quantity - ($orderItem?->quantity ?? 0),
                    'unit' => $m->unit
                ];
            })
            ->toArray();

        $nomenclatureWithdraws = NomenclatureOperation::select(
                'nomenclature_id',
                DB::raw('SUM(quantity) as quantity')
            )
            ->typeWithdraw()
            ->groupBy('nomenclature_id')
            ->pluck('quantity', 'nomenclature_id');

        return inertia('Storehouses/Index', compact('nomenclatureBalances', 'nomenclatureWithdraws'));
    }
}
