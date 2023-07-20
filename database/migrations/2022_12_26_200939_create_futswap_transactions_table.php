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
        Schema::create('futswap_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->string('billId');
            $table->string('status');
            $table->string('secret');
            $table->string('hash')->nullable();
            $table->string('token');
            $table->string('coinName');
            $table->string('address');
            $table->float('value', 10,3);
            $table->string('coinSymbol');
            $table->integer('usdValue');
            $table->string('expires');
            $table->string('time');
            $table->string('paymentUrl');
            $table->float('defaultUnitValue', 10,3);
            $table->float('totalPaid');
            $table->integer('trm');
            $table->string('recoveryFeeTransaction')->nullable();
            $table->string('transactionToMasterWallet')->nullable();
            $table->float('internalFee', 10,3);
            $table->integer('index');
            $table->string('contractAddress');
            $table->string('blockchainSymbol');
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
        Schema::dropIfExists('futswap_transactions');
    }
};
