<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'phone' => $model->phone,
                'discount' => $model->discount,
                'discount_for_single' => $model->discount_for_single,
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

        return to_route('clients.index');
    }

    public function edit(Client $client)
    {
        return inertia('Clients/Edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return to_route('clients.index');
    }
}
