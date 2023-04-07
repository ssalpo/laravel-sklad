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
        return Client::when(request('q'), fn($q, $v) => $q->where('name', 'like', '%' . $v . '$'))
            ->get(['id', 'name']);
    }

    public function nomenclatures()
    {
        return Nomenclature::saleType()
            ->when(request('q'), fn($q, $v) => $q->where('name', 'like', '%' . $v . '%'))
            ->get(['id', 'name']);
    }

    public function orders()
    {
        return array_map(fn($m) => [
            'id' => $m->id,
            'name' => 'Заявка №' . $m->id
        ], Order::when(request('q'), fn($q, $v) => $q->whereId($v))->pluck('id'));
    }
}