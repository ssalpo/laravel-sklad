<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientDebtPaymentRequest;
use App\Services\ClientDebtPaymentService;
use Illuminate\Http\Request;

class ClientDebtPaymentController extends Controller
{
    public function __construct(
        public ClientDebtPaymentService $clientDebtPaymentService
    )
    {
    }

    public function store(int $clientDebt, ClientDebtPaymentRequest $request)
    {
        $this->clientDebtPaymentService->store($clientDebt, $request->validated());

        return back();
    }
}
