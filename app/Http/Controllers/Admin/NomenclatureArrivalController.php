<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NomenclatureArrivalRequest;
use App\Models\Nomenclature;
use App\Models\NomenclatureArrival;
use App\Models\WarehouseMovement;
use App\Enums\WarehouseMovementType;
use App\Services\NomenclatureArrivalService;
use App\Services\Toast;
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
        if (config('warehouse.use_movements')) {
            $nomenclatureArrivals = WarehouseMovement::query()->where('type', WarehouseMovementType::PURCHASE)
                ->with('nomenclature')->orderByDesc('occurred_at')->paginate()->onEachSide(0)
                ->through(fn($model) => [
                    'id' => $model->id, 'nomenclature' => $model->nomenclature->name,
                    'quantity' => $model->quantity, 'unit' => UnitConvertor::UNIT_LABELS[$model->nomenclature->unit],
                    'price_for_sale' => $model->price_for_sale, 'comment' => $model->comment,
                    'arrival_at' => $model->occurred_at->format('d.m.Y H:i'),
                    'created_at' => $model->created_at->format('d.m.Y H:i'),
                    'can_edit' => !$model->reversals()->exists(),
                ]);

            return inertia('NomenclatureArrivals/Index', compact('nomenclatureArrivals'));
        }

        $nomenclatureArrivals = NomenclatureArrival::with(['nomenclature'])
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
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
        $nomenclatures = Nomenclature::query()
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

        Toast::success('Новый приход успешно создан.');

        return to_route('nomenclature-arrivals.index');
    }

    public function edit(int $nomenclatureArrival)
    {
        if (config('warehouse.use_movements')) {
            return $this->editWarehouseMovement($nomenclatureArrival);
        }

        $nomenclatureArrival = NomenclatureArrival::findOrFail($nomenclatureArrival);
        $nomenclatures = Nomenclature::query()
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

        Toast::success('Данные по приходу успешно создан.');

        return to_route('nomenclature-arrivals.index');
    }

    public function editWarehouseMovement(int $id)
    {
        $movement = WarehouseMovement::query()->where('type', WarehouseMovementType::PURCHASE)->findOrFail($id);
        $nomenclatures = Nomenclature::query()->get()->map(fn($model) => ['id' => $model->id, 'name' => $model->name, 'unit' => $model->unit]);

        return inertia('NomenclatureArrivals/Edit', [
            'currentDate' => now()->format('d.m.Y H:i'), 'nomenclatures' => $nomenclatures,
            'nomenclatureArrival' => [
                'id' => $movement->id, 'nomenclature_id' => $movement->nomenclature_id,
                'quantity' => $movement->quantity, 'unit' => $movement->nomenclature->unit,
                'price' => $movement->price, 'price_for_sale' => $movement->price_for_sale,
                'comment' => $movement->comment, 'arrival_at' => $movement->occurred_at->format('d.m.Y H:i'),
                'can_edit' => !$movement->reversals()->exists(),
            ],
        ]);
    }

    public function destroy(int $id)
    {
        $this->nomenclatureArrivalService->destroy($id);

        Toast::success('Приход успешно удален.');

        return to_route('nomenclature-arrivals.index');
    }
}
