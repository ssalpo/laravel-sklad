<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPriceTemplateRequest;
use App\Models\Client;
use App\Models\ClientPriceTemplate;
use App\Models\Nomenclature;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ClientPriceTemplateController extends Controller
{
    public function index(Client $client): Response
    {
        $clientPriceTemplates = ClientPriceTemplate::with(['nomenclature'])
            ->whereClientId($client->id)
            ->paginate()
            ->onEachSide(0)
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature_id' => $model->nomenclature_id,
                'nomenclature' => $model->nomenclature->name,
                'price' => $model->price,
            ]);

        $usedNomenclatureIds = ClientPriceTemplate::whereClientId($client->id)
            ->pluck('nomenclature_id');

        $nomenclatures = Nomenclature::saleType()->get(['id', 'name']);

        return inertia('ClientPriceTemplates/Index', compact(
            'client',
            'clientPriceTemplates',
            'nomenclatures',
            'usedNomenclatureIds'
        ));
    }

    public function store(Client $client, ClientPriceTemplateRequest $request): RedirectResponse
    {
        ClientPriceTemplate::create($request->validated());

        Toast::success('Цена для клиента добавлена.');

        return to_route('client-price-templates.index', $client->id);
    }

    public function update(Client $client, int $clientPriceTemplate, ClientPriceTemplateRequest $request): RedirectResponse
    {
        ClientPriceTemplate::whereClientId($client->id)
            ->findOrFail($clientPriceTemplate)
            ->update($request->validated());

        Toast::success('Цена для клиента обновлена.');

        return to_route('client-price-templates.index', $client->id);
    }

    public function destroy(int $client, int $clientPriceTemplate): RedirectResponse
    {
        ClientPriceTemplate::whereClientId($client)
            ->findOrFail($clientPriceTemplate)
            ->delete();

        Toast::success('Цена для клиента удалена.');

        return to_route('client-price-templates.index', $client);
    }
}
