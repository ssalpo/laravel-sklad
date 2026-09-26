<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_recipes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique(['nomenclature_id', 'version']);
            $table->index(['nomenclature_id', 'is_active']);
        });

        Schema::create('production_recipe_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('production_recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_nomenclature_id')->constrained('nomenclatures')->restrictOnDelete();
            $table->decimal('quantity_per_unit', 16, 6);
            $table->timestamps();
            $table->unique(['production_recipe_id', 'material_nomenclature_id'], 'production_recipe_material_unique');
        });

        Schema::create('production_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
            $table->foreignId('recipe_id')->constrained('production_recipes')->restrictOnDelete();
            $table->decimal('quantity', 16, 6);
            $table->string('status', 20)->default('draft');
            $table->timestamp('produced_at');
            $table->text('comment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['nomenclature_id', 'status']);
            $table->index('produced_at');
        });

        Schema::create('material_inventory_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
            $table->string('type', 40);
            $table->string('direction', 3);
            $table->decimal('quantity', 16, 6);
            $table->nullableMorphs('source');
            $table->foreignId('parent_id')->nullable()->constrained('material_inventory_movements')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->timestamp('occurred_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['nomenclature_id', 'occurred_at']);
            $table->index(['type', 'direction']);
        });

        Schema::create('inventory_counts', function (Blueprint $table): void {
            $table->id();
            $table->string('status', 20)->default('draft');
            $table->text('comment')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('inventory_count_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_count_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nomenclature_id')->constrained()->restrictOnDelete();
            $table->decimal('book_quantity', 16, 6);
            $table->decimal('actual_quantity', 16, 6);
            $table->timestamps();
            $table->unique(['inventory_count_id', 'nomenclature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_count_items');
        Schema::dropIfExists('inventory_counts');
        Schema::dropIfExists('material_inventory_movements');
        Schema::dropIfExists('production_runs');
        Schema::dropIfExists('production_recipe_items');
        Schema::dropIfExists('production_recipes');
    }
};
