<?php

namespace App\Console\Commands;

use App\Models\CashTransactionSaldo;
use App\Services\CashTransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $balance = DB::table('cash_transactions')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-01') AS month_year"),
                DB::raw("ROUND(SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 2 THEN amount ELSE 0 END), 2) AS balance")
            )
            ->whereYear('created_at', $prevMonthDate->format('Y'))
            ->whereMonth('created_at', $prevMonthDate->format('m'))
            ->groupBy('month_year')
            ->first()?->balance ?? 0;

        $prevMonthBalance = $cashTransactionService->getLastMonthDebit($prevMonthDate->subMonth());

        CashTransactionSaldo::create([
            'period' => $prevMonthDate->format('Y-m-01'),
            'balance' => $prevMonthBalance + $balance
        ]);
    }
}
