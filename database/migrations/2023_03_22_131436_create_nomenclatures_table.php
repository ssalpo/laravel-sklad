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
        Schema::create('nomenclatures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained();
            $table->decimal('price_for_sale')->default(0);
            $table->tinyInteger('currency_type')->default(\App\Models\Nomenclature::CURRENCY_TYPE_TJS);
            $table->tinyInteger('type');
            $table->foreignId('unit_id')->constrained();
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
        Schema::dropIfExists('nomenclatures');
    }
};
