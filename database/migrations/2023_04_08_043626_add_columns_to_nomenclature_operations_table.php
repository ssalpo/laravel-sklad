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
        Schema::table('nomenclature_operations', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('order_item_id')->nullable()->constrained();
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclature_operations', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'order_item_id', 'comment']);
        });
    }
};
