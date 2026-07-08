<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\Toast;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $selectedFilter = $request->filled('name') ? $request->only('name') : [];

        $clients = Client::orderBy('created_at', 'DESC')
            ->when($request->input('name'), fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->paginate()
            ->withQueryString()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'phone' => $m->phone,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('Clients/Index', compact('clients', 'selectedFilter'));
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
