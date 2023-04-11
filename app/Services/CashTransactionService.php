<?php

namespace App\Services;

use App\Models\CashTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

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

    public function getLastMonthDebit(Carbon $currentDate)
    {
        $date = $currentDate->subMonth();

        $result = CashTransaction::select(
            'type',
            \DB::raw('SUM(amount) amount')
        )->whereMonth('created_at', $date->format('m'))
            ->whereYear('created_at', $date->format('Y'))
            ->groupBy('type')
            ->get();

        $lastDebit = $result->where('type', CashTransaction::TYPE_DEBIT)->first()?->amount ?? 0;
        $lastCredit = $result->where('type', CashTransaction::TYPE_CREDIT)->first()?->amount ?? 0;

        return $lastDebit - $lastCredit;
    }

    public function getStructuredTransactions()
    {
        $lastMonthDebitAmount = $this->getLastMonthDebit(now());

        return CashTransaction::orderBy('created_at', 'ASC')
            ->whereMonth('created_at', now()->format('m'))
            ->whereYear('created_at', now()->format('Y'))
            ->get()
            ->groupBy(fn($m) => $m->created_at->format('Y-m-d'))
            ->map(function (Collection $items, $key) use (&$lastMonthDebitAmount) {
                $debits = $items->where('type', CashTransaction::TYPE_DEBIT);
                $credits = $items->where('type', CashTransaction::TYPE_CREDIT);

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

                $lastMonthDebitAmount += $debits->sum('amount') - $credits->sum('amount');

                return [
                    'items' => $itemResult,
                    'amount' => $lastMonthDebitAmount
                ];
            });
    }
}
