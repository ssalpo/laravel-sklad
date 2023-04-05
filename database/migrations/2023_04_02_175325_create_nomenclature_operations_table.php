<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('type');
            $table->integer('quantity');
            $table->decimal('price')->default(0);
            $table->decimal('price_for_sale')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclature_operations');
    }
};
