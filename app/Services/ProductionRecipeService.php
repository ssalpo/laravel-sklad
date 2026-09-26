<?php

namespace App\Services;

use App\Models\ProductionRecipe;
use App\Models\ProductionRecipeItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionRecipeService
{
    public function create(array $data): ProductionRecipe
    {
        return DB::transaction(function () use ($data): ProductionRecipe {
            $version = (int) ProductionRecipe::query()->where('nomenclature_id', $data['nomenclature_id'])->max('version') + 1;
            $recipe = ProductionRecipe::create(['nomenclature_id' => $data['nomenclature_id'], 'version' => $version, 'is_active' => false]);
            $this->syncItems($recipe, $data['items']);
            if ($data['is_active'] ?? false) {
                $this->activate($recipe);
            }

            return $recipe->refresh();
        });
    }

    public function update(ProductionRecipe $recipe, array $data): ProductionRecipe
    {
        return DB::transaction(function () use ($recipe, $data): ProductionRecipe {
            $recipe = ProductionRecipe::query()->lockForUpdate()->findOrFail($recipe->id);
            $this->ensureUnused($recipe);
            $this->syncItems($recipe, $data['items']);
            if ($data['is_active'] ?? false) {
                $this->activate($recipe);
            }

            return $recipe->refresh();
        });
    }

    public function createVersion(ProductionRecipe $recipe): ProductionRecipe
    {
        return DB::transaction(function () use ($recipe): ProductionRecipe {
            $recipe->load('items');
            $version = (int) ProductionRecipe::query()->where('nomenclature_id', $recipe->nomenclature_id)->lockForUpdate()->max('version') + 1;
            $copy = ProductionRecipe::create(['nomenclature_id' => $recipe->nomenclature_id, 'version' => $version, 'is_active' => false]);
            foreach ($recipe->items as $item) {
                ProductionRecipeItem::create(['production_recipe_id' => $copy->id, 'material_nomenclature_id' => $item->material_nomenclature_id, 'quantity_per_unit' => $item->quantity_per_unit]);
            }

            return $copy;
        });
    }

    public function activate(ProductionRecipe $recipe): ProductionRecipe
    {
        return DB::transaction(function () use ($recipe): ProductionRecipe {
            ProductionRecipe::query()->where('nomenclature_id', $recipe->nomenclature_id)->update(['is_active' => false]);
            $recipe->update(['is_active' => true]);

            return $recipe->refresh();
        });
    }

    private function syncItems(ProductionRecipe $recipe, array $items): void
    {
        $recipe->items()->delete();
        foreach ($items as $item) {
            ProductionRecipeItem::create(['production_recipe_id' => $recipe->id, 'material_nomenclature_id' => $item['material_nomenclature_id'], 'quantity_per_unit' => $item['quantity_per_unit']]);
        }
    }

    private function ensureUnused(ProductionRecipe $recipe): void
    {
        if ($recipe->runs()->exists()) {
            throw ValidationException::withMessages(['recipe' => 'Версию рецепта, использованную в выпуске, нельзя изменять. Создайте новую версию.']);
        }
    }
}
