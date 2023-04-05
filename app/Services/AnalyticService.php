<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticService
{
    private array $filters = [];

    public function ordersProfitInRange(string|Carbon $from, string|Carbon $to, int $status = Order::STATUS_SEND)
    {
        $dateFrom = is_string($from) ? Carbon::parse($from) : $from;
        $dateTo = is_string($to) ? Carbon::parse($to) : $to;

        return Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->filter(request())
            ->whereStatus($status)
            ->sum('profit');
    }

    public function getNomenclatureTotalsInRange(string|Carbon $from, string|Carbon $to, int $status = Order::STATUS_SEND)
    {
        $dateFrom = is_string($from) ? Carbon::parse($from) : $from;
        $dateTo = is_string($to) ? Carbon::parse($to) : $to;

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
                        Arr::get($this->filters, 'client_id'),
                        fn($q, $v) => $q->where('o.client_id', $v)
                    )
                    ->when(
                        Arr::get($this->filters, 'user_id'),
                        fn($q, $v) => $q->where('o.user_id', $v)
                    )
                    ->whereBetween('o.created_at', [$dateFrom, $dateTo])
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
