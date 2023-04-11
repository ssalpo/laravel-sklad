<?php

namespace App\Http\Controllers\Admin\Cash;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashTransactionRequest;
use App\Models\CashTransaction;
use App\Services\CashTransactionService;
use App\Services\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CashTransactionController extends Controller
{
    public function __construct(
        public CashTransactionService $cashTransactionService
    )
    {
    }

    public function index()
    {
        $filterParams = request()?->collect()->except(['page'])->all();

        $cashTransactions = CashTransaction::orderBy('created_at', 'DESC')
            ->filter($filterParams)
            ->paginate(100)
            ->withQueryString()
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

        return inertia('Cash/CashTransactions/Index', compact('cashTransactions', 'filterParams'));
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
        $filterParams = request()?->all();

        $date = request('date')
            ? Carbon::createFromFormat('n-Y', implode('-', array_values(request('date'))))->addMonth()
            : now();

        $currentMontTotalAmounts = $this->cashTransactionService->getMonthAmounts($date);

        [
            $lastMonthDebitAmount,
            $transactions
        ] = array_values($this->cashTransactionService->getStructuredTransactions($date));


        return inertia(
            'Cash/CashTransactions/StatisticDay',
            compact('currentMontTotalAmounts', 'lastMonthDebitAmount', 'transactions', 'filterParams')
        );
    }
}
