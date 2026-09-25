<?php

namespace Tests\Feature;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Models\Nomenclature;
use App\Models\WarehouseInventory;
use App\Models\WarehouseMovement;
use App\Services\WarehouseInventoryService;
use App\Services\WarehouseMovementService;
use App\Services\WarehouseStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WarehouseInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_posting_creates_inventory_movements_for_surplus_and_shortage(): void
    {
        $first = $this->nomenclature('Первый товар');
        $second = $this->nomenclature('Второй товар');
        app(WarehouseMovementService::class)->income([
            'nomenclature_id' => $first->id,
            'type' => WarehouseMovementType::PURCHASE,
            'quantity' => '10',
        ]);

        $inventory = app(WarehouseInventoryService::class)->create([
            'comment' => 'Плановый пересчёт',
            'items' => [
                ['nomenclature_id' => $first->id, 'actual_quantity' => '8'],
                ['nomenclature_id' => $second->id, 'actual_quantity' => '3'],
            ],
        ]);

        app(WarehouseInventoryService::class)->post($inventory);

        $this->assertDatabaseHas('warehouse_inventories', [
            'id' => $inventory->id,
            'status' => WarehouseInventory::STATUS_POSTED,
        ]);
        $this->assertDatabaseHas('warehouse_movements', [
            'nomenclature_id' => $first->id,
            'type' => WarehouseMovementType::INVENTORY_OUT,
            'direction' => WarehouseMovementDirection::OUT,
            'quantity' => '2.000000',
            'source_type' => WarehouseInventory::class,
            'source_id' => $inventory->id,
        ]);
        $this->assertDatabaseHas('warehouse_movements', [
            'nomenclature_id' => $second->id,
            'type' => WarehouseMovementType::INVENTORY_IN,
            'direction' => WarehouseMovementDirection::IN,
            'quantity' => '3.000000',
            'source_type' => WarehouseInventory::class,
            'source_id' => $inventory->id,
        ]);
        $this->assertSame('8.000000', app(WarehouseStockService::class)->getBalance($first));
        $this->assertSame('3.000000', app(WarehouseStockService::class)->getBalance($second));
    }

    public function test_book_quantity_is_preserved_after_later_movements(): void
    {
        $nomenclature = $this->nomenclature('Товар');
        $movements = app(WarehouseMovementService::class);
        $movements->income([
            'nomenclature_id' => $nomenclature->id,
            'type' => WarehouseMovementType::PURCHASE,
            'quantity' => '10',
        ]);

        $inventory = app(WarehouseInventoryService::class)->create([
            'items' => [['nomenclature_id' => $nomenclature->id, 'actual_quantity' => '10']],
        ]);
        $this->assertDatabaseHas('warehouse_inventory_items', [
            'warehouse_inventory_id' => $inventory->id,
            'book_quantity' => '10.000000',
        ]);

        $movements->expense([
            'nomenclature_id' => $nomenclature->id,
            'type' => WarehouseMovementType::WRITE_OFF,
            'quantity' => '2',
        ]);
        app(WarehouseInventoryService::class)->post($inventory);

        $this->assertSame(2, WarehouseMovement::query()->count());
        $this->assertSame('8.000000', app(WarehouseStockService::class)->getBalance($nomenclature));
    }

    public function test_posted_inventory_cannot_be_changed_or_posted_again(): void
    {
        $nomenclature = $this->nomenclature('Товар');
        $inventory = app(WarehouseInventoryService::class)->create([
            'items' => [['nomenclature_id' => $nomenclature->id, 'actual_quantity' => '0']],
        ]);
        $service = app(WarehouseInventoryService::class);
        $service->post($inventory);

        try {
            $service->update($inventory, [
                'items' => [['nomenclature_id' => $nomenclature->id, 'actual_quantity' => '1']],
            ]);
            $this->fail('Expected a validation exception.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('inventory', $exception->errors());
        }

        $this->expectException(ValidationException::class);
        $service->post($inventory);
    }

    private function nomenclature(string $name): Nomenclature
    {
        return Nomenclature::create([
            'name' => $name,
            'type' => Nomenclature::TYPE_SALE,
            'unit' => 1,
            'price' => 0,
            'price_for_sale' => 0,
            'markup' => 0,
            'dollar_exchange_rate' => 0,
        ]);
    }
}
