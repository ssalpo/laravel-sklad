<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use App\Services\Toast;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        public ClientService $clientService
    )
    {
    }

    public function create()
    {
        return inertia('My/Clients/Edit');
    }

    public function store(ClientRequest $clientRequest)
    {
        $client = $this->clientService->store($clientRequest->validated());

        Toast::success('Новый клиент добавлен!');

        return to_route('my.orders.create', ['clientId' => $client->id]);
    }
}
