<?php

namespace App\Http\Controllers\Admin\Cash;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashTransactionRequest;
use App\Models\CashTransaction;
use App\Services\CashTransactionService;
use App\Services\Toast;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function __construct(
        public CashTransactionService $cashTransactionService
    )
    {
    }

    public function index()
    {
        $cashTransactions = CashTransaction::orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($model) => [
                'id' => $model->id,
                'type' => $model->type,
                'type_label' => CashTransaction::TYPE_LABELS[$model->type],
                'amount' => $model->amount,
                'comment' => $model->comment,
                'status' => $model->status,
                'status_label' => CashTransaction::STATUS_LABELS[$model->status],
                'created_at' => $model->created_at->format('d-m-Y H:i')
            ]);

        return inertia('Cash/CashTransactions/Index', compact('cashTransactions'));
    }

    public function store(CashTransactionRequest $request)
    {
        $this->cashTransactionService->store($request->validated());

        Toast::success('Новая транзакция успешно создана.');

        return to_route('cash-transactions.index');
    }

    public function update(int $cashTransaction, CashTransactionRequest $request)
    {
        $this->cashTransactionService->update($cashTransaction, $request->validated());

        Toast::success('Новая транзакция успешно создана.');

        return to_route('cash-transactions.index');
    }

    public function dayStatistics()
    {
        $transactions = $this->cashTransactionService->getStructuredTransactions();

        return inertia('Cash/CashTransactions/StatisticDay', compact('transactions'));
    }
}
