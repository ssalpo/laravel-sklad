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
        Schema::create('mixture_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained();
            $table->tinyInteger('currency_type')->default(\App\Models\Nomenclature::CURRENCY_TYPE_USD);
            $table->double('weight')->default(0);
            $table->foreignId('weight_unit_id')->constrained('units');
            $table->double('water')->default(0);
            $table->foreignId('water_unit_id')->constrained('units');
            $table->double('worker_price')->default(0);
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
        Schema::dropIfExists('mixture_compositions');
    }
};
