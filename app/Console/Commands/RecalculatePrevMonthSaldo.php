<?php

namespace App\Console\Commands;

use App\Models\CashTransaction;
use App\Models\CashTransactionSaldo;
use App\Services\CashTransactionService;
use Illuminate\Console\Command;

class RecalculatePrevMonthSaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recalculate:prev:month:saldo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate previous month saldo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CashTransactionService $cashTransactionService)
    {
        $prevMonthDate = now()->subMonth();

        $transactions = CashTransaction::select(
            'type',
            \DB::raw('SUM(amount) amount')
        )->onlyMonth($prevMonthDate)
            ->completed()
            ->groupBy('type')
            ->get();

        $balance = $transactions->where('type', CashTransaction::TYPE_DEBIT)->sum('amount') - $transactions->where('type', CashTransaction::TYPE_CREDIT)->sum('amount');

        $prevMonthBalance = $cashTransactionService->getLastMonthDebit($prevMonthDate);

        CashTransactionSaldo::create([
            'period' => $prevMonthDate->format('Y-m-01'),
            'balance' => $prevMonthBalance + $balance
        ]);
    }
}
