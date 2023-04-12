<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function clients()
    {
        return Client::when(request('q'), fn($q, $v) => $q->where('name', 'like', '%' . $v . '$'))
            ->get(['id', 'name']);
    }

    public function nomenclatures()
    {
        return Nomenclature::saleType()
            ->when(request('q'), fn($q, $v) => $q->where('name', 'like', '%' . $v . '%'))
            ->get(['id', 'name']);
    }

    public function users()
    {
        return User::when(request('q'), fn($q, $v) => $q->where('name', 'like', '%' . $v . '%'))
            ->get(['id', 'name']);
    }

    public function orders()
    {
        return Order::when(request('q'), fn($q, $v) => $q->whereId($v))
            ->when(\request()?->has('without_debt'), fn($q) => $q->whereDoesntHave('debt'))
            ->get()
            ->transform(fn($m) => [
                'id' => $m->id,
                'name' => 'Заявка №' . $m->id
            ]);
    }
}
