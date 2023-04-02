<?php

namespace App\Services;

use App\Models\ClientDebt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientDebtPaymentService
{
    public $relatedToCurrentUser;

    public function store(int $clientDebtId, array $data)
    {
        $clientDebt = ClientDebt::with('payments')
            ->whereClientId($data['client_id'])
            ->whereIsPaid(false)
            ->when($this->relatedToCurrentUser, fn($q) => $q->relatedToMe())
            ->findOrFail($clientDebtId);

        $alreadyPaidAmount = $clientDebt->payments->sum('amount');
        $endPaidAmount = (double) $alreadyPaidAmount + $data['amount'];

        if ($endPaidAmount > $clientDebt->amount) {
            throw ValidationException::withMessages([
                'amount' => sprintf('Максимальное значение суммы должно быть равно %s сом.', $clientDebt->amount - $alreadyPaidAmount)
            ]);
        }

        return DB::transaction(static function () use ($clientDebt, $data, $endPaidAmount) {
            $payment = $clientDebt->payments()->create($data);

            if ($clientDebt->amount === $endPaidAmount) {
                $clientDebt->markAsPaid();
            }

            return $payment;
        });
    }

    public function setRelatedToCurrentUser(): self
    {
        $this->relatedToCurrentUser = true;

        return $this;
    }
}
