<?php

namespace App\Http\Controllers;

use App\Http\Requests\MixtureCompositionRequest;
use App\Models\MixtureComposition;
use App\Models\Nomenclature;
use App\Models\Unit;
use Illuminate\Http\Request;

class MixtureCompositionController extends Controller
{
    public function index()
    {
        $mixtureCompositions = MixtureComposition::with(['nomenclature', 'weightUnit', 'waterUnit'])->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'weight' => $model->weight,
                'weight_unit' => $model->weightUnit->name,
                'water' => $model->water,
                'water_unit' => $model->waterUnit->name,
                'worker_price' => $model->worker_price
            ]);

        return inertia('MixtureCompositions/Index', compact('mixtureCompositions'));
    }

    public function create()
    {
        $units = Unit::all();
        $nomenclatures = Nomenclature::saleType()->get(['id', 'name']);

        return inertia('MixtureCompositions/Edit', compact('units', 'nomenclatures'));
    }

    public function store(MixtureCompositionRequest $request)
    {
        MixtureComposition::create($request->validated());

        return to_route('mixture-compositions.index');
    }

    public function edit(MixtureComposition $mixtureComposition)
    {
        $units = Unit::all();
        $nomenclatures = Nomenclature::saleType()->get(['id', 'name']);

        return inertia('MixtureCompositions/Edit', compact('mixtureComposition', 'units', 'nomenclatures'));
    }

    public function update(MixtureComposition $mixtureComposition, MixtureCompositionRequest $request)
    {
        $mixtureComposition->update($request->validated());

        return to_route('mixture-compositions.index');
    }
}
