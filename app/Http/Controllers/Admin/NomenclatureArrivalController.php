<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NomenclatureArrivalRequest;
use App\Models\Nomenclature;
use App\Models\NomenclatureArrival;
use App\Services\NomenclatureArrivalService;
use App\Services\UnitConvertor;
use Illuminate\Support\Carbon;

class NomenclatureArrivalController extends Controller
{
    public function __construct(
        public NomenclatureArrivalService $nomenclatureArrivalService
    )
    {
    }

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
                'created_at' => $model->created_at->format('d.m.Y H:i'),
                'can_edit' => $model->can_edit
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

        $currentDate = date('d.m.Y H:i');

        return inertia('NomenclatureArrivals/Edit', compact('nomenclatures', 'currentDate'));
    }

    public function store(NomenclatureArrivalRequest $request)
    {
        $this->nomenclatureArrivalService->store($request->validated());

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

        $currentDate = date('d.m.Y H:i');

        return inertia('NomenclatureArrivals/Edit', [
            'currentDate' => $currentDate,
            'nomenclatures' => $nomenclatures,
            'nomenclatureArrival' => [
                'id' => $nomenclatureArrival->id,
                'nomenclature_id' => $nomenclatureArrival->nomenclature_id,
                'quantity' => $nomenclatureArrival->quantity,
                'unit' => $nomenclatureArrival->unit,
                'price' => $nomenclatureArrival->price,
                'price_for_sale' => $nomenclatureArrival->price_for_sale,
                'comment' => $nomenclatureArrival->comment,
                'arrival_at' => $nomenclatureArrival->arrival_at?->format('d.m.Y H:i'),
                'can_edit' => $nomenclatureArrival->can_edit
            ]
        ]);
    }

    public function update(NomenclatureArrivalRequest $request, int $nomenclatureArrival)
    {
        $this->nomenclatureArrivalService->update($nomenclatureArrival, $request->validated());

        return to_route('nomenclature-arrivals.index');
    }
}
