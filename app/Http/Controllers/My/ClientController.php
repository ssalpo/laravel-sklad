<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function create()
    {
        return inertia('My/Clients/Edit');
    }
    public function store(ClientRequest $clientRequest)
    {
        $client = Client::create($clientRequest->validated());

        return to_route('my.orders.create', ['clientId' => $client->id]);
    }
}
