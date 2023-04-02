<?php

namespace App\Services;

use App\Models\ClientDebt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientDebtPaymentService
{
    public function store(int $clientDebtId, array $data)
    {
        $clientDebt = ClientDebt::with('payments')->relatedToMe()
            ->findOrFail($clientDebtId);

        $alreadyPaidAmount = $clientDebt->payments->sum('amount');

        if (($alreadyPaidAmount + $data['amount']) > $clientDebt->amount) {
            throw ValidationException::withMessages([
                'amount' => sprintf('Максимальное значение суммы должно быть равно %s сом.', $clientDebt->amount - $alreadyPaidAmount)
            ]);
        }

        return DB::transaction(static function () use ($clientDebt, $data) {
            $payment = $clientDebt->payments()->create($data);


        });
    }
}
