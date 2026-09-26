<?php

namespace Tests\Feature;

use App\Enums\MaterialInventoryMovementDirection;
use App\Enums\MaterialInventoryMovementType;
use App\Models\InventoryCount;
use App\Models\MaterialInventoryMovement;
use App\Models\Nomenclature;
use App\Models\ProductionRun;
use App\Services\InventoryCountService;
use App\Services\MaterialInventoryMovementService;
use App\Services\MaterialInventoryStockService;
use App\Services\ProductionRecipeService;
use App\Services\ProductionRunService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MaterialInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_consumes_recipe_materials_and_keeps_finished_goods_separate(): void
    {
        [$product, $first, $second] = $this->nomenclatures();
        $stock = app(MaterialInventoryMovementService::class);
        $stock->income(['nomenclature_id' => $first->id, 'type' => MaterialInventoryMovementType::RECEIPT, 'quantity' => '10']);
        $stock->income(['nomenclature_id' => $second->id, 'type' => MaterialInventoryMovementType::RECEIPT, 'quantity' => '20']);
        $recipe = app(ProductionRecipeService::class)->create(['nomenclature_id' => $product->id, 'is_active' => true, 'items' => [
            ['material_nomenclature_id' => $first->id, 'quantity_per_unit' => '1.5'],
            ['material_nomenclature_id' => $second->id, 'quantity_per_unit' => '2'],
        ]]);
        $run = app(ProductionRunService::class)->create(['nomenclature_id' => $product->id, 'quantity' => '4', 'produced_at' => now(), 'comment' => null]);
        app(ProductionRunService::class)->complete($run);

        $this->assertSame(ProductionRun::STATUS_COMPLETED, $run->fresh()->status);
        $this->assertDatabaseCount('material_inventory_movements', 4);
        $this->assertDatabaseHas('material_inventory_movements', ['source_type' => ProductionRun::class, 'source_id' => $run->id, 'nomenclature_id' => $first->id, 'quantity' => '6.000000', 'direction' => MaterialInventoryMovementDirection::OUT]);
        $this->assertSame('4.000000', app(MaterialInventoryStockService::class)->getBalance($first));
        $this->assertSame('12.000000', app(MaterialInventoryStockService::class)->getBalance($second));
        $this->assertSame('0.000000', app(MaterialInventoryStockService::class)->getBalance($product));
        $this->assertSame($recipe->id, $run->fresh()->recipe_id);
    }

    public function test_production_is_atomic_when_one_material_is_insufficient(): void
    {
        [$product, $first, $second] = $this->nomenclatures();
        app(MaterialInventoryMovementService::class)->income(['nomenclature_id' => $first->id, 'type' => MaterialInventoryMovementType::RECEIPT, 'quantity' => '10']);
        app(ProductionRecipeService::class)->create(['nomenclature_id' => $product->id, 'is_active' => true, 'items' => [['material_nomenclature_id' => $first->id, 'quantity_per_unit' => '1'], ['material_nomenclature_id' => $second->id, 'quantity_per_unit' => '1']]]);
        $run = app(ProductionRunService::class)->create(['nomenclature_id' => $product->id, 'quantity' => '1', 'produced_at' => now(), 'comment' => null]);
        try {
            app(ProductionRunService::class)->complete($run);
            $this->fail('Expected insufficient stock validation.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('quantity', $exception->errors());
        }
        $this->assertSame(1, MaterialInventoryMovement::query()->count());
        $this->assertSame(ProductionRun::STATUS_DRAFT, $run->fresh()->status);
    }

    public function test_cancelling_production_creates_reversal_movements(): void
    {
        [$product, $material] = $this->nomenclatures();
        app(MaterialInventoryMovementService::class)->income(['nomenclature_id' => $material->id, 'type' => MaterialInventoryMovementType::RECEIPT, 'quantity' => '5']);
        app(ProductionRecipeService::class)->create(['nomenclature_id' => $product->id, 'is_active' => true, 'items' => [['material_nomenclature_id' => $material->id, 'quantity_per_unit' => '2']]]);
        $run = app(ProductionRunService::class)->create(['nomenclature_id' => $product->id, 'quantity' => '2', 'produced_at' => now(), 'comment' => null]);
        app(ProductionRunService::class)->complete($run);
        app(ProductionRunService::class)->cancel($run);

        $this->assertSame(ProductionRun::STATUS_CANCELLED, $run->fresh()->status);
        $this->assertDatabaseHas('material_inventory_movements', ['source_id' => $run->id, 'type' => MaterialInventoryMovementType::REVERSAL, 'direction' => MaterialInventoryMovementDirection::IN, 'quantity' => '4.000000']);
        $this->assertSame('5.000000', app(MaterialInventoryStockService::class)->getBalance($material));
    }

    public function test_used_recipe_is_immutable_and_new_version_preserves_history(): void
    {
        [$product, $material] = $this->nomenclatures();
        $recipes = app(ProductionRecipeService::class);
        $recipe = $recipes->create(['nomenclature_id' => $product->id, 'is_active' => true, 'items' => [['material_nomenclature_id' => $material->id, 'quantity_per_unit' => '1']]]);
        ProductionRun::create(['nomenclature_id' => $product->id, 'recipe_id' => $recipe->id, 'quantity' => '1', 'produced_at' => now(), 'status' => ProductionRun::STATUS_DRAFT]);
        $this->expectException(ValidationException::class);
        try {
            $recipes->update($recipe, ['items' => [['material_nomenclature_id' => $material->id, 'quantity_per_unit' => '2']]]);
        } finally {
            $copy = $recipes->createVersion($recipe);
            $recipes->activate($copy);
            $this->assertSame(2, $copy->version);
            $this->assertTrue($copy->fresh()->is_active);
            $this->assertFalse($recipe->fresh()->is_active);
        }
    }

    public function test_inventory_count_records_snapshot_and_adjustment(): void
    {
        [, $material] = $this->nomenclatures();
        app(MaterialInventoryMovementService::class)->income(['nomenclature_id' => $material->id, 'type' => MaterialInventoryMovementType::RECEIPT, 'quantity' => '10']);
        $service = app(InventoryCountService::class);
        $count = $service->create(['comment' => 'Проверка', 'items' => [['nomenclature_id' => $material->id, 'actual_quantity' => '8']]]);
        $this->assertDatabaseHas('inventory_count_items', ['inventory_count_id' => $count->id, 'book_quantity' => '10.000000']);
        $service->complete($count);
        $this->assertSame(InventoryCount::STATUS_COMPLETED, $count->fresh()->status);
        $this->assertDatabaseHas('material_inventory_movements', ['source_type' => InventoryCount::class, 'source_id' => $count->id, 'type' => MaterialInventoryMovementType::INVENTORY_SHORTAGE, 'quantity' => '2.000000']);
        $this->assertSame('8.000000', app(MaterialInventoryStockService::class)->getBalance($material));
    }

    private function nomenclatures(): array
    {
        $product = $this->nomenclature('Продукт', Nomenclature::TYPE_SALE);
        $first = $this->nomenclature('Материал 1', Nomenclature::TYPE_COMPOSITE);
        $second = $this->nomenclature('Материал 2', Nomenclature::TYPE_COMPOSITE);

        return [$product, $first, $second];
    }

    private function nomenclature(string $name, int $type): Nomenclature
    {
        return Nomenclature::create(['name' => $name, 'type' => $type, 'unit' => 2, 'price' => 0, 'price_for_sale' => 0, 'markup' => 0, 'dollar_exchange_rate' => 0]);
    }
}
