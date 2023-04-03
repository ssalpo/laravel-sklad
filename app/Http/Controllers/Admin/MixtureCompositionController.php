<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MixtureCompositionRequest;
use App\Models\MixtureComposition;
use App\Models\Nomenclature;
use App\Services\MixtureCompositionService;
use App\Services\Toast;
use App\Services\UnitConvertor;

class MixtureCompositionController extends Controller
{
    public function __construct(
        public MixtureCompositionService $mixtureCompositionService
    )
    {
    }

    public function index()
    {
        $mixtureCompositions = MixtureComposition::with(['nomenclature'])
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'weight' => $model->weight,
                'weight_unit' => UnitConvertor::UNIT_LABELS[$model->weight_unit],
                'water' => $model->water,
                'water_unit' => UnitConvertor::UNIT_LABELS[$model->water_unit],
                'worker_price' => $model->worker_price
            ]);

        return inertia('MixtureCompositions/Index', compact('mixtureCompositions'));
    }

    public function create()
    {
        $nomenclatures = Nomenclature::saleType()
            ->whereNotIn('id', MixtureComposition::pluck('nomenclature_id'))
            ->get(['id', 'name']);

        return inertia('MixtureCompositions/Edit', compact('nomenclatures'));
    }

    public function store(MixtureCompositionRequest $request)
    {
        MixtureComposition::create($request->validated());

        Toast::success('Новый состав успешно добавлен!');

        return to_route('mixture-compositions.index');
    }

    public function show(int $mixtureCompositionId)
    {
        $mixtureComposition = MixtureComposition::with('nomenclature')
            ->with('mixtureCompositionItems.nomenclature')
            ->findOrFail($mixtureCompositionId);

        $totalSum = $this->mixtureCompositionService->calculateTotalSum($mixtureComposition);

        return inertia('MixtureCompositions/Show', [
            'totalSum' => $totalSum,
            'mixtureComposition' => [
                'id' => $mixtureComposition->id,
                'nomenclature' => $mixtureComposition->nomenclature->name,
                'worker_price' => $mixtureComposition->worker_price,
                'weight' => $mixtureComposition->weight,
                'weightUnit' => UnitConvertor::UNIT_LABELS[$mixtureComposition->weight_unit],
                'water' => $mixtureComposition->water,
                'waterUnit' => UnitConvertor::UNIT_LABELS[$mixtureComposition->water_unit],
                'mixtureCompositionItems' => $mixtureComposition->mixtureCompositionItems->transform(fn($model) => [
                    'id' => $model->id,
                    'nomenclature' => $model->nomenclature->name,
                    'price' => $model->price,
                    'quantity' => $model->quantity,
                    'end_result' => $model->end_result,
                    'unit' => UnitConvertor::UNIT_LABELS[$model->unit]
                ])
            ]
        ]);
    }

    public function edit(MixtureComposition $mixtureComposition)
    {
        $nomenclatures = Nomenclature::saleType()
            ->whereNotIn(
                'id',
                MixtureComposition::whereNot('nomenclature_id', $mixtureComposition->nomenclature_id)
                    ->pluck('nomenclature_id')
            )
            ->get(['id', 'name']);

        return inertia('MixtureCompositions/Edit', compact('mixtureComposition', 'nomenclatures'));
    }

    public function update(MixtureComposition $mixtureComposition, MixtureCompositionRequest $request)
    {
        $mixtureComposition->update($request->validated());

        Toast::success('Новый состав успешно добавлен!');

        return to_route('mixture-compositions.index');
    }
}
