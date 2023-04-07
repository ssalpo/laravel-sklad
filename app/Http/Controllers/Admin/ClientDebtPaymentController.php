<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientDebtPaymentRequest;
use App\Models\ClientDebt;
use App\Services\ClientDebtPaymentService;
use App\Services\Toast;

class ClientDebtPaymentController extends Controller
{
    public function __construct(
        public ClientDebtPaymentService $clientDebtPaymentService
    )
    {
    }

    public function index(int $clientId, int $debtId)
    {
        $clientDebt = ClientDebt::whereClientId($clientId)
            ->with('payments')
            ->findOrFail($debtId);

        $queryParams = ['client' => $clientId, 'debt' => $debtId];

        $debt = [
            'order' => $clientDebt->order_id,
            'payments' => $clientDebt->payments->transform(fn($m) => [
                'id' => $m->id,
                'amount' => $m->amount,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ])
        ];

        return inertia('ClientDebtPayments/Index', compact('queryParams', 'debt'));
    }

    public function store(int $clientId, int $clientDebt, ClientDebtPaymentRequest $request)
    {
        $this->clientDebtPaymentService->store($clientDebt, $request->validated() + ['client_id' => $clientId]);

        Toast::success('Погашение долга проведено.');

        return back();
    }

    public function destroy(int $clientId, int $clientDebt, int $clientDebtPaymentId)
    {
        $this->clientDebtPaymentService->destroy(
            $clientDebt,
            $clientDebtPaymentId
        );

        Toast::success('Успешно удалено.');

        return back();
    }
}
