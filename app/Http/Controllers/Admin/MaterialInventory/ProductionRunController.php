<?php

namespace App\Http\Controllers\Admin\MaterialInventory;

use App\Enums\MaterialInventoryMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialInventory\ProductionRunRequest;
use App\Models\MaterialInventoryMovement;
use App\Models\Nomenclature;
use App\Models\ProductionRun;
use App\Services\ProductionRunService;
use App\Services\Toast;
use App\Services\UnitConvertor;

class ProductionRunController extends Controller
{
    public function index()
    {
        $runs = ProductionRun::query()->with(['nomenclature', 'recipe', 'createdBy'])->latest('produced_at')->paginate()->withQueryString()
            ->through(fn (ProductionRun $run) => $this->runData($run));

        return inertia('ProductionRuns/Index', ['runs' => $runs, 'statusLabels' => ProductionRun::statusLabels()]);
    }

    public function create()
    {
        return inertia('ProductionRuns/Edit', $this->editProps());
    }

    public function store(ProductionRunRequest $request, ProductionRunService $service)
    {
        $run = $service->create($request->validated());
        Toast::success('Черновик выпуска создан.');

        return to_route('production-runs.show', $run);
    }

    public function show(ProductionRun $productionRun)
    {
        $productionRun->load(['nomenclature', 'recipe.items.material', 'createdBy']);
        $consumption = MaterialInventoryMovement::query()->where('source_type', ProductionRun::class)->where('source_id', $productionRun->id)->where('type', MaterialInventoryMovementType::PRODUCTION_CONSUMPTION)->with('nomenclature')->get()->map(fn ($item) => ['material' => $item->nomenclature->name, 'unit' => UnitConvertor::UNIT_LABELS[$item->nomenclature->unit], 'quantity' => $item->quantity]);

        return inertia('ProductionRuns/Show', ['run' => $this->runData($productionRun), 'recipe_items' => $productionRun->recipe->items->map(fn ($item) => ['material' => $item->material->name, 'unit' => UnitConvertor::UNIT_LABELS[$item->material->unit], 'quantity_per_unit' => $item->quantity_per_unit, 'quantity' => bcmul($item->quantity_per_unit, $productionRun->quantity, 6)]), 'consumption' => $consumption, 'statusLabels' => ProductionRun::statusLabels()]);
    }

    public function edit(ProductionRun $productionRun)
    {
        return inertia('ProductionRuns/Edit', $this->editProps($productionRun));
    }

    public function update(ProductionRun $productionRun, ProductionRunRequest $request, ProductionRunService $service)
    {
        $service->update($productionRun, $request->validated());
        Toast::success('Черновик выпуска сохранён.');

        return to_route('production-runs.show', $productionRun);
    }

    public function complete(ProductionRun $productionRun, ProductionRunService $service)
    {
        $service->complete($productionRun);
        Toast::success('Производство проведено, материалы списаны.');

        return to_route('production-runs.show', $productionRun);
    }

    public function cancel(ProductionRun $productionRun, ProductionRunService $service)
    {
        $service->cancel($productionRun);
        Toast::success('Производство отменено, материалы возвращены на склад.');

        return to_route('production-runs.show', $productionRun);
    }

    private function editProps(?ProductionRun $run = null): array
    {
        return ['run' => $run ? ['id' => $run->id, 'nomenclature_id' => $run->nomenclature_id, 'quantity' => $run->quantity, 'produced_at' => $run->produced_at->format('Y-m-d\\TH:i'), 'comment' => $run->comment, 'status' => $run->status] : null, 'products' => Nomenclature::query()->saleType()->orderBy('name')->get(['id', 'name'])->all()];
    }

    private function runData(ProductionRun $run): array
    {
        return ['id' => $run->id, 'nomenclature' => $run->nomenclature->name, 'unit' => UnitConvertor::UNIT_LABELS[$run->nomenclature->unit], 'quantity' => $run->quantity, 'recipe_version' => $run->recipe->version, 'status' => $run->status, 'produced_at' => $run->produced_at->format('d.m.Y H:i'), 'comment' => $run->comment, 'created_by' => $run->createdBy?->name];
    }
}
