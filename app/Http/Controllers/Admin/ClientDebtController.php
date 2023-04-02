<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientDebtRequest;
use App\Models\Client;
use App\Models\ClientDebt;
use App\Models\Order;
use App\Services\ClientDebtService;
use Illuminate\Support\Facades\DB;

class ClientDebtController extends Controller
{
    public function __construct(
        public ClientDebtService $clientDebtService
    )
    {
    }

    public function allClientDebts()
    {
        $debts = ClientDebt::select(
            DB::raw('client_debts.client_id'),
            DB::raw('clients.name AS client_name'),
            DB::raw('SUM(client_debts.amount) AS totalAmount'),
        )->join('clients', 'clients.id', '=', 'client_debts.client_id')
            ->where('client_debts.is_paid', false)
            ->groupBy('client_debts.client_id')
            ->get()
            ->toArray();

        return inertia('ClientDebts/All', compact('debts'));
    }

    public function index(Client $client)
    {
        $debts = ClientDebt::whereClientId($client->id)
            ->withSum('payments', 'amount')
            ->get()
            ->transform(fn($m) => [
            'id' => $m->id,
            'order_id' => $m->order_id,
            'amount' => $m->amount,
            'comment' => $m->comment,
            'payments_sum_amount' => $m->payments_sum_amount,
            'is_paid' => $m->is_paid,
        ]);

        return inertia('ClientDebts/Index', [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ],
            'debts' => $debts
        ]);
    }

    public function create(Client $client)
    {
        $orders = Order::get()->transform(fn($m) => [
            'id' => $m->id,
            'client_id' => $m->client_id,
        ]);

        $selectedOrder = request('order');

        return inertia('ClientDebts/Edit', [
            'orders' => $orders,
            'selectedOrder' => $selectedOrder,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ]
        ]);
    }

    public function store(int $clientId, ClientDebtRequest $request)
    {
        $this->clientDebtService->store($request->validated() + ['client_id' => $clientId]);

        return to_route('client.debts.index', $clientId);
    }

    public function edit(Client $client, int $id)
    {
        $debt = $client->debts()->findOrFail($id);

        return inertia('ClientDebts/Edit', [
            'debt' => [
                'id' => $debt->id,
                'order_id' => $debt->order_id,
                'amount' => $debt->amount,
                'comment' => $debt->comment,
            ],
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ]
        ]);
    }

    public function update(int $clientId, int $id, ClientDebtRequest $request)
    {
        $this->clientDebtService->update($id, $request->validated() + ['client_id' => $clientId]);

        return to_route('client.debts.index', $clientId);
    }

    public function destroy(int $clientId, int $id)
    {
        $this->clientDebtService->destroy($clientId, $id);

        return to_route('client.debts.index', $clientId);
    }
}
