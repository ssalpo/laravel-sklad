<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function store(array $data)
    {
        return Client::create($data);
    }
}
