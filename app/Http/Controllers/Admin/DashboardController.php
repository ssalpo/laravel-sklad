<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayProfit = Order::whereDate('created_at', now())->statusSend()->sum('profit');

        $monthProfit = Order::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->statusSend()->sum('profit');

        $todayNomenclatureProfits = DB::select('
            SELECT t.nomenclature_id AS id, n.name, SUM(t.profit) AS profit
            FROM (
                     SELECT nomenclature_id,
                            (price_for_sale - price) * quantity AS profit
                     FROM order_items
                        INNER JOIN orders o ON order_items.order_id = o.id AND o.status = ?
                     WHERE DATE(order_items.created_at) = ?
                 ) AS t
                     INNER JOIN nomenclatures n ON n.id = t.nomenclature_id
            GROUP BY t.nomenclature_id;
        ', [Order::STATUS_SEND, now()->format('Y-m-d')]);


        $monthNomenclatureProfits = DB::select('
            SELECT t.nomenclature_id AS id, n.name, SUM(t.profit) AS profit
            FROM (
                     SELECT nomenclature_id,
                            (price_for_sale - price) * quantity AS profit
                     FROM order_items
                        INNER JOIN orders o ON order_items.order_id = o.id AND o.status = ?
                     WHERE order_items.created_at BETWEEN ? AND ?
                 ) AS t
                     INNER JOIN nomenclatures n ON n.id = t.nomenclature_id
            GROUP BY t.nomenclature_id;
        ', [Order::STATUS_SEND, now()->startOfMonth(), now()->endOfMonth()]);

        return inertia('Dashboard', compact(
            'todayProfit',
            'monthProfit',
            'todayNomenclatureProfits',
            'monthNomenclatureProfits'
        ));
    }
}
