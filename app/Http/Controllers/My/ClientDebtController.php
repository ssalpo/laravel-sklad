<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\ClientDebt;
use Illuminate\Http\Request;

class ClientDebtController extends Controller
{
    public function index()
    {
        $debts = ClientDebt::relatedToMe()
            ->filter(request()?->all())
            ->with(['order', 'client', 'user'])
            ->withSum('payments', 'amount')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'client' => [
                    'id' => $m->client->id,
                    'name' => $m->client->name
                ],
                'payments_sum_amount' => $m->payments_sum_amount,
                'order_id' => $m->order_id,
                'amount' => $m->amount,
                'comment' => $m->comment,
                'is_paid' => $m->is_paid,
                'created_at' => $m->created_at?->format('d-m-Y H:i'),
            ]);

        return inertia('My/ClientDebts/Index', compact('debts'));
    }
}
