<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('warehouse_inventories')) {
            Schema::create('warehouse_inventories', function (Blueprint $table): void {
                $table->id();
                $table->string('status', 20)->default('draft');
                $table->text('comment')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('posted_at')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('posted_at');
            });
        }

        if (! Schema::hasTable('warehouse_inventory_items')) {
            Schema::create('warehouse_inventory_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('warehouse_inventory_id')->constrained()->cascadeOnDelete();
                $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
                $table->decimal('book_quantity', 16, 6);
                $table->decimal('actual_quantity', 16, 6);
                $table->timestamps();

                $table->unique(['warehouse_inventory_id', 'nomenclature_id'], 'warehouse_inventory_item_nomenclature_unique');
            });
        } elseif ($this->missingUniqueIndex()) {
            Schema::table('warehouse_inventory_items', function (Blueprint $table): void {
                $table->unique(['warehouse_inventory_id', 'nomenclature_id'], 'warehouse_inventory_item_nomenclature_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventory_items');
        Schema::dropIfExists('warehouse_inventories');
    }

    private function missingUniqueIndex(): bool
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return false;
        }

        return empty(DB::select(
            'SHOW INDEX FROM `warehouse_inventory_items` WHERE Key_name = ?',
            ['warehouse_inventory_item_nomenclature_unique']
        ));
    }
};
