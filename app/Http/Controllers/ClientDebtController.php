<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDebt;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDebtController extends Controller
{
    public function index()
    {
        $debts = ClientDebt::select(
            DB::raw('client_debts.client_id'),
            DB::raw('clients.name AS client_name'),
            DB::raw('SUM(client_debts.amount) AS totalAmount'),
        )->join('clients', 'clients.id', '=', 'client_debts.client_id')
            ->groupBy('client_debts.client_id')
            ->get()
            ->toArray();

        return inertia('ClientDebts/Index', compact('debts'));
    }

    public function show(Client $client)
    {
        $debts = ClientDebt::get()->transform(fn($m) => [
            'id' => $m->id,
            'order_id' => $m->order_id,
            'amount' => $m->amount,
            'comment' => $m->comment,
            'is_paid' => $m->is_paid,
        ]);

        return inertia('ClientDebts/Show', [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ],
            'debts' => $debts
        ]);
    }

    public function markAsPaid(Client $client, ClientDebt $clientDebt)
    {
        $clientDebt->update(['is_paid' => true]);

        return to_route('client-debts.show', $client->id);
    }
}
