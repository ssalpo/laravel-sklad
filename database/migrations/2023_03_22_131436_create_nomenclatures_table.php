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
            $table->decimal('price')->default(0);
            $table->decimal('price_for_sale')->default(0);
            $table->decimal('dollar_exchange_rate')->default(0)->comment('Курс доллара для расчета себестоимости');
            $table->decimal('markup')->default(0)->comment('Наценка');
            $table->tinyInteger('type');
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
        Schema::dropIfExists('nomenclatures');
    }
};
