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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('nomenclature_id')->constrained();
            $table->double('price')->default(0);
            $table->double('price_for_sale')->default(0);
            $table->tinyInteger('currency_type')->default(\App\Models\Nomenclature::CURRENCY_TYPE_TJS);
            $table->integer('quantity')->default(0);
            $table->foreignId('unit_id')->constrained();
            $table->double('discount')->default(0);
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
        Schema::dropIfExists('order_items');
    }
};
