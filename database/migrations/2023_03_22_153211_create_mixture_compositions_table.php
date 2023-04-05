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
            $table->foreignId('nomenclature_id')->constrained()->cascadeOnDelete();
            $table->double('weight')->default(0);
            $table->tinyInteger('weight_unit');
            $table->double('water')->default(0);
            $table->tinyInteger('water_unit');
            $table->double('worker_price')->default(0);
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
        Schema::dropIfExists('mixture_compositions');
    }
};
