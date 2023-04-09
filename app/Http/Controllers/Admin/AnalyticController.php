<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticService;

class AnalyticController extends Controller
{
    public function __construct(
        public AnalyticService $analyticService
    )
    {
    }

    public function range()
    {
        $filterParams = request()?->all();

        $dateFrom = request()?->input('date.0');
        $dateTo = request()?->input('date.1');

        $profit = $this->analyticService->setFilters($filterParams)
            ->ordersProfitInRange($dateFrom, $dateTo);

        $nomenclatureProfits = $this->analyticService->setFilters($filterParams)
            ->getNomenclatureTotalsInRange($dateFrom, $dateTo);

        return inertia('Analytics/Range', compact('profit', 'nomenclatureProfits', 'filterParams'));
    }
}
