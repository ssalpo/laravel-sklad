<?php

namespace App\Http\Controllers\Admin\Cash;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashTransactionDollarExchangeRequest;
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
            ->onEachSide(0)
            ->withQueryString()
            ->through(fn($model) => [
                'id' => $model->id,
                'type' => $model->type,
                'type_label' => CashTransaction::TYPE_LABELS[$model->type],
                'amount' => $model->amount,
                'amount_in_dollar' => $model->amount_in_dollar,
                'dollar_exchange_rate' => $model->dollar_exchange_rate,
                'comment' => $model->comment,
                'status' => $model->status,
                'is_irrevocably' => $model->is_irrevocably,
                'order_id' => $model->order_id,
                'nomenclature_operation_id' => $model->nomenclature_operation_id,
                'client_debt_id' => $model->client_debt_id,
                'client_debt_payment_id' => $model->client_debt_payment_id,
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

        Toast::success('Данные успешно обновлены.');

        return to_route('cash-transactions.index');
    }

    public function destroy(int $id)
    {
        $this->cashTransactionService->destroy($id);

        Toast::success('Успешно удалено.');

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

    public function dollarExchange(int $cashTransaction, CashTransactionDollarExchangeRequest $request)
    {
        $this->cashTransactionService->dollarExchange(
            $cashTransaction,
            $request->dollar_exchange_rate
        );

        return back();
    }
}
