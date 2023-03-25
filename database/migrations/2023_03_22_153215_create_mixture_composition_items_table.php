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
        Schema::create('mixture_composition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mixture_composition_id')->constrained();
            $table->foreignId('nomenclature_id')->constrained();
            $table->double('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->tinyInteger('unit');
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
        Schema::dropIfExists('mixture_composition_items');
    }
};
