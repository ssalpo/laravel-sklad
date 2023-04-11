<?php

namespace App\Services;

use App\Models\CashTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CashTransactionService
{
    public function store(array $data): CashTransaction
    {
        return CashTransaction::create($data);
    }

    public function update(int $cashTransactionId, array $data): CashTransaction
    {
        $cashTransaction = CashTransaction::findOrFail($cashTransactionId);

        $cashTransaction->update($data);

        return $cashTransaction;
    }

    public function getLastMonthDebit(Carbon $currentDate): float
    {
        $date = $currentDate->subMonth();

        $result = CashTransaction::select(
            'type',
            \DB::raw('SUM(amount) amount')
        )->whereMonth('created_at', $date->format('m'))
            ->completed()
            ->whereYear('created_at', $date->format('Y'))
            ->groupBy('type')
            ->get();

        $lastDebit = $result->where('type', CashTransaction::TYPE_DEBIT)->sum('amount');
        $lastCredit = $result->where('type', CashTransaction::TYPE_CREDIT)->sum('amount');

        return $lastDebit - $lastCredit;
    }

    public function getMonthAmounts(Carbon $date)
    {
        $transactions = CashTransaction::select(
            'type',
            \DB::raw('SUM(amount) amount')
        )->whereMonth('created_at', $date->format('m'))
            ->completed()
            ->whereYear('created_at', $date->format('Y'))
            ->groupBy('type')
            ->get();

        return [
            'debit' => $transactions->where('type', CashTransaction::TYPE_DEBIT)->sum('amount'),
            'credit' => $transactions->where('type', CashTransaction::TYPE_CREDIT)->sum('amount'),
        ];
    }

    public function getStructuredTransactions(Carbon $currentDate): array
    {
        $lastMonthDebitAmount = $this->getLastMonthDebit($currentDate->clone());
        $lastAmount = $lastMonthDebitAmount;

        $transactions = CashTransaction::orderBy('created_at', 'ASC')
            ->completed()
            ->whereMonth('created_at', $currentDate->format('m'))
            ->whereYear('created_at', $currentDate->format('Y'))
            ->get()
            ->groupBy(fn($m) => $m->created_at->format('d-m-Y'))
            ->map(function (Collection $items, $key) use (&$lastAmount) {
                $debits = $items->where('type', CashTransaction::TYPE_DEBIT)->values();
                $credits = $items->where('type', CashTransaction::TYPE_CREDIT)->values();
                $amounts = [$debits->sum('amount'), $credits->sum('amount')];

                $maxCount = max([$debits->count(), $credits->count()]);

                $itemResult = [];

                for ($i = 0; $i < $maxCount; $i++) {
                    $debit = $debits[$i] ?? null;
                    $credit = $credits[$i] ?? null;

                    $itemResult[] = [
                        'debit_comment' => $debit['comment'] ?? null,
                        'debit' => $debit['amount'] ?? null,
                        'credit' => $credit['amount'] ?? null,
                        'credit_comment' => $credit['comment'] ?? null,
                    ];
                }

                $lastAmount +=  $amounts[0] - $amounts[1];

                return [
                    'items' => $itemResult,
                    'debit_amount' => $amounts[0],
                    'credit_amount' => $amounts[1],
                    'amount' => $lastAmount
                ];
            });

        return compact('lastMonthDebitAmount', 'transactions');
    }
}
