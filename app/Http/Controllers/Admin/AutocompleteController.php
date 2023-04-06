<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\Order;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function clients()
    {
        return Client::all(['id', 'name']);
    }

    public function nomenclatures()
    {
        return Nomenclature::saleType()->get(['id', 'name']);
    }

    public function orders()
    {
        return array_map(fn($m) => [
            'id' => $m->id,
            'name' => 'Заявка №' . $m->id
        ], Order::pluck('id'));
    }
}
