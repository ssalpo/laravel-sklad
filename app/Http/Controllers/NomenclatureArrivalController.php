<?php

namespace App\Http\Controllers;

use App\Http\Requests\NomenclatureArrivalRequest;
use App\Http\Requests\NomenclatureRequest;
use App\Models\Nomenclature;
use App\Models\NomenclatureArrival;
use App\Services\UnitConvertor;
use Illuminate\Http\Request;

class NomenclatureArrivalController extends Controller
{

    public function index()
    {
        $nomenclatureArrivals = NomenclatureArrival::with(['nomenclature'])
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'quantity' => $model->quantity,
                'unit' => UnitConvertor::UNIT_LABELS[$model->unit],
                'price_for_sale' => $model->price_for_sale,
                'comment' => $model->comment,
                'arrival_at' => $model->arrival_at->format('d.m.Y H:i'),
            ]);

        return inertia('NomenclatureArrivals/Index', compact('nomenclatureArrivals'));
    }


    public function create()
    {
        $nomenclatures = Nomenclature::saleType()
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'unit' => $model->unit,
            ]);

        $currentDate = date('yyyy-MM-ddThh:mm');

        return inertia('NomenclatureArrivals/Edit', compact('nomenclatures', 'currentDate'));
    }

    public function store(NomenclatureArrivalRequest $request)
    {
        NomenclatureArrival::create($request->validated());

        return to_route('nomenclature-arrivals.index');
    }

    public function edit(NomenclatureArrival $nomenclatureArrival)
    {
        $nomenclatures = Nomenclature::saleType()
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'unit' => $model->unit,
            ]);

        return inertia('NomenclatureArrivals/Edit', [
            'nomenclatures' => $nomenclatures,
            'nomenclatureArrival' => [
                'id' => $nomenclatureArrival->id,
                'nomenclature_id' => $nomenclatureArrival->nomenclature_id,
                'quantity' => $nomenclatureArrival->quantity,
                'unit' => $nomenclatureArrival->unit,
                'price' => $nomenclatureArrival->price,
                'price_for_sale' => $nomenclatureArrival->price_for_sale,
                'comment' => $nomenclatureArrival->comment,
                'arrival_at' => $nomenclatureArrival->arrival_at?->format('d.m.Y H:i')
            ]
        ]);
    }

    public function update(NomenclatureArrivalRequest $request, NomenclatureArrival $nomenclatureArrival)
    {
        $nomenclatureArrival->update($request->validated());

        return to_route('nomenclature-arrivals.index');
    }
}
