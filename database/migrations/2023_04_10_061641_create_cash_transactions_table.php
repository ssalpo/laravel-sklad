<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('Тип операции');
            $table->tinyInteger('status')->default(\App\Models\CashTransaction::STATUS_COMPLETED);
            $table->double('amount');
            $table->double('amount_in_dollar')->default(0);
            $table->double('dollar_exchange_rate')->default(0);
            $table->boolean('is_irrevocably')->default(false)->comment('Безвозвратный тип операции');
            $table->text('comment')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('nomenclature_operation_id')->nullable()->constrained();
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
        Schema::dropIfExists('cash_transactions');
    }
};
