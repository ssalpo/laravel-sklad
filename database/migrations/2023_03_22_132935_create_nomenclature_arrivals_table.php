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
        Schema::create('nomenclature_arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomenclature_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('unit');
            $table->double('quantity')->default(0);
            $table->text('comment')->nullable();
            $table->timestamp('arrival_at')->useCurrent();
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
        Schema::dropIfExists('nomenclature_arrivals');
    }
};
