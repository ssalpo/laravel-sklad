<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticService
{
    private array $filters = [];

    public function ordersProfitInRange(string|Carbon|null $from, string|Carbon|null $to, int $status = Order::STATUS_SEND)
    {
        $dateFrom = $from ? Carbon::parse($from)->startOfDay() : null;
        $dateTo = $to ? Carbon::parse($to)->endOfDay() : null;

        return Order::whereBetween('send_at', [$dateFrom, $dateTo])
            ->filter(request())
            ->whereStatus($status)
            ->sum('profit');
    }

    public function ordersAmountInRange(string|Carbon|null $from, string|Carbon|null $to, int $status = Order::STATUS_SEND)
    {
        $dateFrom = $from ? Carbon::parse($from)->startOfDay() : null;
        $dateTo = $to ? Carbon::parse($to)->endOfDay() : null;

        return Order::whereBetween('send_at', [$dateFrom, $dateTo])
            ->filter(request())
            ->whereStatus($status)
            ->sum('amount');
    }

    public function getNomenclatureTotalsInRange(string|Carbon|null $from, string|Carbon|null $to, int $status = Order::STATUS_SEND)
    {
        $dateFrom = $from ? Carbon::parse($from)->startOfDay() : null;
        $dateTo = $to ? Carbon::parse($to)->endOfDay() : null;

        return DB::table(DB::raw('order_items o_i'))
            ->select(
                DB::raw('n.id'),
                DB::raw('n.name'),
                DB::raw('SUM(o_i.price_for_sale * o_i.quantity) AS amount'),
                DB::raw('SUM((o_i.price_for_sale - o_i.price) * o_i.quantity) AS profit'),
            )
            ->join(
                DB::raw('orders o'),
                fn($q) => $q->on('o.id', '=', 'o_i.order_id')
                    ->where('o.status', $status)
                    ->when(
                        Arr::get($this->filters, 'client'),
                        fn($q, $v) => $q->where('o.client_id', $v)
                    )
                    ->when(
                        Arr::get($this->filters, 'user'),
                        fn($q, $v) => $q->where('o.user_id', $v)
                    )
                    ->whereBetween('o.send_at', [$dateFrom, $dateTo])
            )
            ->join(
                DB::raw('nomenclatures n'),
                fn($q) => $q->on('n.id', '=', 'o_i.nomenclature_id')
                    ->whereNull('n.deleted_at')
            )
            ->groupBy('n.id')
            ->get();
    }

    public function setFilters(array $data)
    {
        $this->filters = $data;

        return $this;
    }
}
