<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRefundRequest;
use App\Services\NomenclatureOperationService;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NomenclatureOperationController extends Controller
{
    public function __construct(
        public NomenclatureOperationService $nomenclatureOperationService
    )
    {
    }

    public function refundOrder(OrderRefundRequest $request): RedirectResponse
    {
        $this->nomenclatureOperationService
            ->setRelatedToMe()
            ->refundOrder($request->validated());

        Toast::success('Номенклатура успешно возвращена.');

        return back();
    }
}
