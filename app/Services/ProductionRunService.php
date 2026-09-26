<?php

namespace App\Services;

use App\Enums\MaterialInventoryMovementType;
use App\Models\MaterialInventoryMovement;
use App\Models\ProductionRecipe;
use App\Models\ProductionRun;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionRunService
{
    public function __construct(private MaterialInventoryMovementService $movements)
    {
    }

    public function create(array $data): ProductionRun
    {
        return ProductionRun::create(array_merge($data, ['recipe_id' => $this->activeRecipeId($data['nomenclature_id']), 'status' => ProductionRun::STATUS_DRAFT, 'created_by' => auth()->id()]));
    }

    public function update(ProductionRun $run, array $data): ProductionRun
    {
        if ($run->status !== ProductionRun::STATUS_DRAFT) {
            throw ValidationException::withMessages(['run' => 'Проведённый или отменённый выпуск нельзя изменить.']);
        }
        $data['recipe_id'] = (int) $data['nomenclature_id'] === (int) $run->nomenclature_id ? $run->recipe_id : $this->activeRecipeId($data['nomenclature_id']);
        $run->update($data);

        return $run->refresh();
    }

    public function complete(ProductionRun $run): ProductionRun
    {
        return DB::transaction(function () use ($run): ProductionRun {
            $run = ProductionRun::query()->lockForUpdate()->with('recipe.items')->findOrFail($run->id);
            if ($run->status !== ProductionRun::STATUS_DRAFT) {
                throw ValidationException::withMessages(['run' => 'Можно провести только черновик выпуска.']);
            }
            if ($run->recipe->items->isEmpty()) {
                throw ValidationException::withMessages(['recipe' => 'В рецепте нет материалов.']);
            }
            foreach ($run->recipe->items as $item) {
                $this->movements->expense(['nomenclature_id' => $item->material_nomenclature_id, 'type' => MaterialInventoryMovementType::PRODUCTION_CONSUMPTION,
                    'quantity' => bcmul($item->quantity_per_unit, $run->quantity, 6), 'source_type' => ProductionRun::class, 'source_id' => $run->id,
                    'comment' => "Выпуск производства #{$run->id}", 'occurred_at' => $run->produced_at]);
            }
            $run->update(['status' => ProductionRun::STATUS_COMPLETED]);

            return $run->refresh();
        });
    }

    public function cancel(ProductionRun $run): ProductionRun
    {
        return DB::transaction(function () use ($run): ProductionRun {
            $run = ProductionRun::query()->lockForUpdate()->findOrFail($run->id);
            if ($run->status !== ProductionRun::STATUS_COMPLETED) {
                throw ValidationException::withMessages(['run' => 'Можно отменить только проведённый выпуск.']);
            }
            $movements = MaterialInventoryMovement::query()->where('source_type', ProductionRun::class)->where('source_id', $run->id)->where('type', MaterialInventoryMovementType::PRODUCTION_CONSUMPTION)->get();
            foreach ($movements as $movement) {
                $this->movements->reverse($movement, "Отмена выпуска производства #{$run->id}");
            }
            $run->update(['status' => ProductionRun::STATUS_CANCELLED]);

            return $run->refresh();
        });
    }

    private function activeRecipeId(int $nomenclatureId): int
    {
        $recipe = ProductionRecipe::query()->where('nomenclature_id', $nomenclatureId)->where('is_active', true)->first();
        if (! $recipe) {
            throw ValidationException::withMessages(['nomenclature_id' => 'Для продукции нет активной рецептуры.']);
        }

        return $recipe->id;
    }
}
