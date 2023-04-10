<?php

namespace App\Services;

use App\Models\CashTransaction;

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
}
