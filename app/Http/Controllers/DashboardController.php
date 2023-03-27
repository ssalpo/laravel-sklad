<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayProfit = Order::whereDate('created_at', now())->sum('profit');

        $monthProfit = Order::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->sum('profit');

        $todayNomenclatureProfits = DB::select('
            SELECT t.nomenclature_id AS id, n.name, SUM(t.profit) AS profit
            FROM (
                     SELECT nomenclature_id,
                            (price_for_sale - price) * quantity AS profit
                     FROM order_items
                     WHERE DATE(created_at) = ?
                 ) AS t
                     INNER JOIN nomenclatures n ON n.id = t.nomenclature_id
            GROUP BY t.nomenclature_id;
        ', [now()->format('Y-m-d')]);


        $monthNomenclatureProfits = DB::select('
            SELECT t.nomenclature_id AS id, n.name, SUM(t.profit) AS profit
            FROM (
                     SELECT nomenclature_id,
                            (price_for_sale - price) * quantity AS profit
                     FROM order_items
                     WHERE created_at BETWEEN ? AND ?
                 ) AS t
                     INNER JOIN nomenclatures n ON n.id = t.nomenclature_id
            GROUP BY t.nomenclature_id;
        ', [now()->startOfMonth(), now()->endOfMonth()]);

        return inertia('Dashboard', compact(
            'todayProfit',
            'monthProfit',
            'todayNomenclatureProfits',
            'monthNomenclatureProfits'
        ));
    }
}
