<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticService;

class DashboardController extends Controller
{
    public function __construct(
        public AnalyticService $analyticService
    )
    {
    }

    public function index()
    {
        $startOfDayDate = now()->startOfDay();
        $endOfDayDate = now()->endOfDay();

        $startOfMonthDate = now()->startOfMonth();
        $endOfMonthDate = now()->endOfMonth();

        $todayProfit = $this->analyticService->ordersProfitInRange($startOfDayDate, $endOfDayDate);
        $monthProfit = $this->analyticService->ordersProfitInRange($startOfMonthDate, $endOfMonthDate);

        $todayNomenclatureProfits = $this->analyticService->getNomenclatureTotalsInRange($startOfDayDate, $endOfDayDate);
        $monthNomenclatureProfits = $this->analyticService->getNomenclatureTotalsInRange($startOfMonthDate, $endOfMonthDate);

        return inertia('Dashboard', compact(
            'todayProfit',
            'monthProfit',
            'todayNomenclatureProfits',
            'monthNomenclatureProfits'
        ));
    }
}
