<?php

namespace App\Http\Controllers\Admin\MaterialInventory;

use App\Enums\MaterialInventoryMovementDirection;
use App\Enums\MaterialInventoryMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialInventory\MaterialMovementRequest;
use App\Models\InventoryCount;
use App\Models\MaterialInventoryMovement;
use App\Models\Nomenclature;
use App\Models\ProductionRun;
use App\Services\MaterialInventoryMovementService;
use App\Services\MaterialInventoryStockService;
use App\Services\Toast;
use App\Services\UnitConvertor;

class MaterialInventoryController extends Controller
{
    public function balances(MaterialInventoryStockService $stock)
    {
        $query = Nomenclature::query()->compositeType()->orderBy('name');
        $query->when(request('search'), fn ($q, $search) => $q->where('name', 'like', "%{$search}%"));
        $materials = $query->get();
        $totals = $stock->getTotals($materials->pluck('id'));

        return inertia('MaterialInventory/Balances', ['materials' => $materials->map(fn (Nomenclature $material) => array_merge(['id' => $material->id, 'name' => $material->name, 'unit' => UnitConvertor::UNIT_LABELS[$material->unit]], $totals->get($material->id, ['incoming' => '0.000000', 'outgoing' => '0.000000', 'balance' => '0.000000'])))->values(), 'filters' => request()->only('search')]);
    }

    public function movements()
    {
        $filters = request()->only(['from', 'to', 'nomenclature_id', 'type', 'direction']);
        $movements = MaterialInventoryMovement::query()->with(['nomenclature', 'createdBy'])
            ->when($filters['from'] ?? null, fn ($q, $date) => $q->whereDate('occurred_at', '>=', $date))
            ->when($filters['to'] ?? null, fn ($q, $date) => $q->whereDate('occurred_at', '<=', $date))
            ->when($filters['nomenclature_id'] ?? null, fn ($q, $id) => $q->where('nomenclature_id', $id))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->when($filters['direction'] ?? null, fn ($q, $direction) => $q->where('direction', $direction))
            ->orderByDesc('occurred_at')->orderByDesc('id')->paginate()->withQueryString()
            ->through(fn (MaterialInventoryMovement $movement) => ['id' => $movement->id, 'occurred_at' => $movement->occurred_at->format('d.m.Y H:i'), 'nomenclature' => $movement->nomenclature->name, 'unit' => UnitConvertor::UNIT_LABELS[$movement->nomenclature->unit], 'type' => $movement->type, 'type_label' => MaterialInventoryMovementType::labels()[$movement->type] ?? $movement->type, 'direction' => $movement->direction, 'quantity' => $movement->quantity, 'comment' => $movement->comment, 'source' => $this->sourceLabel($movement), 'created_by' => $movement->createdBy?->name]);

        return inertia('MaterialInventory/Movements', ['movements' => $movements, 'filters' => $filters, 'materials' => $this->materials(), 'types' => MaterialInventoryMovementType::labels(), 'directions' => MaterialInventoryMovementDirection::labels()]);
    }

    public function createReceipt()
    {
        return inertia('MaterialInventory/MovementForm', $this->formProps('receipt'));
    }

    public function createAdjustmentIn()
    {
        return inertia('MaterialInventory/MovementForm', $this->formProps('adjustment_in'));
    }

    public function createAdjustmentOut()
    {
        return inertia('MaterialInventory/MovementForm', $this->formProps('adjustment_out'));
    }

    public function createWriteOff()
    {
        return inertia('MaterialInventory/MovementForm', $this->formProps('write_off'));
    }

    public function createReturn()
    {
        return inertia('MaterialInventory/MovementForm', $this->formProps('return_in', true));
    }

    public function storeReceipt(MaterialMovementRequest $request, MaterialInventoryMovementService $movements)
    {
        $movements->income(array_merge($request->validated(), ['type' => MaterialInventoryMovementType::RECEIPT]));
        Toast::success('Поступление материала создано.');

        return to_route('material-inventory.balances');
    }

    public function storeAdjustmentIn(MaterialMovementRequest $request, MaterialInventoryMovementService $movements)
    {
        $movements->income(array_merge($request->validated(), ['type' => MaterialInventoryMovementType::ADJUSTMENT_IN]));
        Toast::success('Корректировка увеличения остатка создана.');

        return to_route('material-inventory.balances');
    }

    public function storeAdjustmentOut(MaterialMovementRequest $request, MaterialInventoryMovementService $movements)
    {
        $movements->expense(array_merge($request->validated(), ['type' => MaterialInventoryMovementType::ADJUSTMENT_OUT]));
        Toast::success('Корректировка уменьшения остатка создана.');

        return to_route('material-inventory.balances');
    }

    public function storeWriteOff(MaterialMovementRequest $request, MaterialInventoryMovementService $movements)
    {
        $movements->expense(array_merge($request->validated(), ['type' => MaterialInventoryMovementType::WRITE_OFF]));
        Toast::success('Списание материала создано.');

        return to_route('material-inventory.balances');
    }

    public function storeReturn(MaterialMovementRequest $request, MaterialInventoryMovementService $movements)
    {
        $data = $request->validated();
        if (! empty($data['production_run_id'])) {
            $data['source_type'] = ProductionRun::class;
            $data['source_id'] = $data['production_run_id'];
        }
        unset($data['production_run_id']);
        $movements->income(array_merge($data, ['type' => MaterialInventoryMovementType::RETURN_IN]));
        Toast::success('Возврат материала создан.');

        return to_route('material-inventory.balances');
    }

    private function materials(): array
    {
        return Nomenclature::query()->compositeType()->orderBy('name')->get(['id', 'name', 'unit'])->map(fn ($m) => ['id' => $m->id, 'name' => $m->name, 'unit' => UnitConvertor::UNIT_LABELS[$m->unit]])->all();
    }

    private function formProps(string $operation, bool $withRuns = false): array
    {
        return ['operation' => $operation, 'materials' => $this->materials(), 'runs' => $withRuns ? ProductionRun::query()->where('status', ProductionRun::STATUS_COMPLETED)->with('nomenclature')->latest('id')->get()->map(fn ($run) => ['id' => $run->id, 'name' => "#{$run->id} — {$run->nomenclature->name}"])->all() : []];
    }

    private function sourceLabel(MaterialInventoryMovement $movement): ?string
    {
        return match ($movement->source_type) {
            ProductionRun::class => "Производство #{$movement->source_id}", InventoryCount::class => "Инвентаризация #{$movement->source_id}", default => null
        };
    }
}
