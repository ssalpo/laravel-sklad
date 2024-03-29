<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\Toast;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'phone' => $m->phone,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('Clients/Index', compact('clients'));
    }

    public function create()
    {
        return inertia('Clients/Edit');
    }

    public function store(ClientRequest $request)
    {
        Client::create($request->validated());

        Toast::success('Новый клиент успешно добавлен.');

        return to_route('clients.index');
    }

    public function edit(Client $client)
    {
        return inertia('Clients/Edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        Toast::success('Данные клиента успешно изменены.');

        return to_route('clients.index');
    }
}
