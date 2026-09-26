<?php

namespace App\Http\Controllers\Admin\MaterialInventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialInventory\ProductionRecipeRequest;
use App\Models\Nomenclature;
use App\Models\ProductionRecipe;
use App\Services\ProductionRecipeService;
use App\Services\Toast;
use App\Services\UnitConvertor;

class ProductionRecipeController extends Controller
{
    public function index()
    {
        $recipes = ProductionRecipe::query()->with('nomenclature')->withCount('runs')->latest('id')->paginate()->withQueryString()
            ->through(fn (ProductionRecipe $recipe) => ['id' => $recipe->id, 'nomenclature' => $recipe->nomenclature->name, 'version' => $recipe->version, 'is_active' => $recipe->is_active, 'runs_count' => $recipe->runs_count]);

        return inertia('ProductionRecipes/Index', compact('recipes'));
    }

    public function create()
    {
        return inertia('ProductionRecipes/Edit', $this->editProps());
    }

    public function store(ProductionRecipeRequest $request, ProductionRecipeService $service)
    {
        $recipe = $service->create($request->validated());
        Toast::success('Рецептура создана.');

        return to_route('production-recipes.edit', $recipe);
    }

    public function edit(ProductionRecipe $productionRecipe)
    {
        $productionRecipe->load(['items.material', 'nomenclature']);

        return inertia('ProductionRecipes/Edit', $this->editProps($productionRecipe));
    }

    public function update(ProductionRecipe $productionRecipe, ProductionRecipeRequest $request, ProductionRecipeService $service)
    {
        $service->update($productionRecipe, $request->validated());
        Toast::success('Рецептура сохранена.');

        return to_route('production-recipes.edit', $productionRecipe);
    }

    public function activate(ProductionRecipe $productionRecipe, ProductionRecipeService $service)
    {
        $service->activate($productionRecipe);
        Toast::success('Версия рецептуры активирована.');

        return to_route('production-recipes.edit', $productionRecipe);
    }

    public function createVersion(ProductionRecipe $productionRecipe, ProductionRecipeService $service)
    {
        $copy = $service->createVersion($productionRecipe);
        Toast::success('Создана новая версия рецептуры.');

        return to_route('production-recipes.edit', $copy);
    }

    private function editProps(?ProductionRecipe $recipe = null): array
    {
        return ['recipe' => $recipe ? ['id' => $recipe->id, 'nomenclature_id' => $recipe->nomenclature_id, 'nomenclature' => $recipe->nomenclature?->name, 'version' => $recipe->version, 'is_active' => $recipe->is_active, 'is_used' => $recipe->runs()->exists(), 'items' => $recipe->items->map(fn ($item) => ['material_nomenclature_id' => $item->material_nomenclature_id, 'material' => $item->material->name, 'unit' => UnitConvertor::UNIT_LABELS[$item->material->unit], 'quantity_per_unit' => $item->quantity_per_unit])->values()] : null,
            'products' => Nomenclature::query()->saleType()->orderBy('name')->get(['id', 'name'])->all(),
            'materials' => Nomenclature::query()->compositeType()->orderBy('name')->get(['id', 'name', 'unit'])->map(fn ($item) => ['id' => $item->id, 'name' => $item->name, 'unit' => UnitConvertor::UNIT_LABELS[$item->unit]])->all()];
    }
}
