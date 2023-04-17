<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\ClientDebt;
use App\Models\ClientDebtPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientDebtPaymentService
{
    public $relatedToCurrentUser;

    public function store(int $clientDebtId, array $data): ClientDebtPayment
    {
        $clientDebt = ClientDebt::with('payments')
            ->whereClientId($data['client_id'])
            ->whereIsPaid(false)
            ->when($this->relatedToCurrentUser, fn($q) => $q->relatedToMe())
            ->findOrFail($clientDebtId);

        $alreadyPaidAmount = $clientDebt->payments->sum('amount');
        $endPaidAmount = (double)$alreadyPaidAmount + $data['amount'];

        if ($endPaidAmount > $clientDebt->amount) {
            throw ValidationException::withMessages([
                'amount' => sprintf('Максимальное значение суммы должно быть равно %s сом.', $clientDebt->amount - $alreadyPaidAmount)
            ]);
        }

        return DB::transaction(function () use ($clientDebt, $data, $endPaidAmount) {
            $payment = $clientDebt->payments()->create($data);

            if ($clientDebt->amount === $endPaidAmount) {
                $clientDebt->markAsPaid();
            }

            $this->cashTransaction($payment);

            return $payment;
        });
    }

    public function destroy(int $clientDebtId, int $clientDebtPaymentId): ClientDebtPayment
    {
        $payment = ClientDebtPayment::whereClientDebtId($clientDebtId)
            ->findOrFail($clientDebtPaymentId);

        $payment->delete();

        return $payment;
    }

    public function cashTransaction(ClientDebtPayment $clientDebtPayment): Model|ClientDebtPayment
    {
        $clientDebt = $clientDebtPayment->clientDebt;

        $comment = sprintf(
            'Погашение долга по заявке №%s на сумму %s сом.',
            $clientDebt->order_id,
            $clientDebtPayment->amount
        );

        return $clientDebtPayment->cashTransaction()->updateOrCreate(['client_debt_payment_id' => $clientDebtPayment->id], [
            'type' => CashTransaction::TYPE_DEBIT,
            'amount' => $clientDebtPayment->amount,
            'comment' => $comment
        ]);
    }

    public function setRelatedToCurrentUser(): self
    {
        $this->relatedToCurrentUser = true;

        return $this;
    }
}
