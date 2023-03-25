<?php

namespace App\Http\Controllers;

use App\Http\Requests\NomenclatureRequest;
use App\Models\Nomenclature;
use App\UnitConvertor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class NomenclatureController extends Controller
{
    public function index(): Response
    {
        $nomenclatures = Nomenclature::paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'price_for_sale' => $model->price_for_sale,
                'currency_type' => $model->currency_type,
                'type' => $model->type,
                'unit' => UnitConvertor::UNIT_LABELS[$model->unit],
            ]);

        return inertia('Nomenclatures/Index', compact('nomenclatures'));
    }

    public function create(): Response
    {
        $currencyTypes = Nomenclature::CURRENCY_TYPES;
        $nomenclatureTypes = Nomenclature::TYPES_LIST;

        return inertia('Nomenclatures/Edit', compact(
            'currencyTypes',
            'nomenclatureTypes'
        ));
    }


    public function store(NomenclatureRequest $request): RedirectResponse
    {
        Nomenclature::create($request->validated());

        return to_route('nomenclatures.index');
    }

    public function edit(Nomenclature $nomenclature): Response
    {
        $currencyTypes = Nomenclature::CURRENCY_TYPES;
        $nomenclatureTypes = Nomenclature::TYPES_LIST;

        return inertia('Nomenclatures/Edit', compact(
            'nomenclature',
            'currencyTypes',
            'nomenclatureTypes'
        ));
    }

    public function update(NomenclatureRequest $request, Nomenclature $nomenclature): RedirectResponse
    {
        $nomenclature->update($request->validated());

        return to_route('nomenclatures.index');
    }
}
