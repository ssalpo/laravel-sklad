<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientDiscountRequest;
use App\Models\Client;
use App\Models\ClientDiscount;
use App\Models\Nomenclature;


class ClientDiscountController extends Controller
{
    public function index(Client $client)
    {
        $clientDiscounts = ClientDiscount::with(['nomenclature'])
            ->whereClientId($client->id)
            ->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'discount' => $model->discount
            ]);

        return inertia('ClientDiscounts/Index', compact('client', 'clientDiscounts'));
    }

    public function create(Client $client)
    {
        $nomenclatures = Nomenclature::saleType()
            ->whereNotIn('id', ClientDiscount::whereClientId($client->id)->pluck('nomenclature_id'))
            ->get(['id', 'name']);

        return inertia('ClientDiscounts/Edit', compact('client', 'nomenclatures'));
    }

    public function store(Client $client, ClientDiscountRequest $request)
    {
        ClientDiscount::create($request->validated());

        return to_route('client-discounts.index', $client->id);
    }

    public function edit(Client $client, int $discountId)
    {
        $clientDiscount = ClientDiscount::whereClientId($client->id)->findOrFail($discountId);

        $nomenclatures = Nomenclature::saleType()
            ->whereNotIn('id', ClientDiscount::whereClientId($client->id)->whereNot('id', $discountId)->pluck('nomenclature_id'))
            ->get(['id', 'name']);

        return inertia('ClientDiscounts/Edit', [
            'nomenclatures' => $nomenclatures,
            'client' => [
                'id' => $client->id,
                'name' => $client->name
            ],
            'clientDiscount' => [
                'id' => $clientDiscount->id,
                'nomenclature_id' => $clientDiscount->nomenclature_id,
                'discount' => $clientDiscount->discount,
            ]
        ]);
    }

    public function update(Client $client, int $discountId, ClientDiscountRequest $request)
    {
        ClientDiscount::whereClientId($client->id)->findOrFail($discountId)
            ->update($request->validated());

        return to_route('client-discounts.index', $client->id);
    }
}
