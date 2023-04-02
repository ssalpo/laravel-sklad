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

    public function store(int $clientId, int $clientDebt, ClientDebtPaymentRequest $request)
    {
        $this->clientDebtPaymentService
            ->setRelatedToCurrentUser()
            ->store($clientDebt, $request->validated() + ['client_id' => $clientId]);

        return back();
    }
}
