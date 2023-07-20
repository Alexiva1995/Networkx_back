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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('liquidation_id')->nullable()->unsigned()->comment('id de liquidacion');
            $table->foreign('liquidation_id')->references('id')->on('liquidactions')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('wallets_commissions_id')->nullable()->unsigned()->comment('id de la wallet ');
            $table->foreign('wallets_commissions_id')->references('id')->on('wallets_commissions')->onUpdate('cascade')->onDelete('cascade');
            $table->double('amount')->nullable();
            $table->double('amount_retired')->nullable();
            $table->double('amount_available')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 - waiting, 1 - paid, 2-cancelled');
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
        Schema::dropIfExists('transactions');
    }
};
