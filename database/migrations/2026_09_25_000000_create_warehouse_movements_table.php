<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
            $table->string('type', 40);
            $table->string('direction', 3);
            $table->decimal('quantity', 16, 6);
            $table->decimal('price', 16, 6)->nullable();
            $table->decimal('price_for_sale', 16, 6)->nullable();
            $table->nullableMorphs('source');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('warehouse_movements')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->timestamp('occurred_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('legacy_source_type', 80)->nullable();
            $table->unsignedBigInteger('legacy_source_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('nomenclature_id');
            $table->index('direction');
            $table->index('type');
            $table->index('occurred_at');
            $table->index(['nomenclature_id', 'occurred_at']);
            $table->unique(['legacy_source_type', 'legacy_source_id'], 'warehouse_movements_legacy_source_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_movements');
    }
};
