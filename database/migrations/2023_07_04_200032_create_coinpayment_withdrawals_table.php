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
        Schema::create('coinpayment_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tx_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('amounti')->nullable();
            $table->string('amountf')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->string('coin')->nullable();
            $table->string('send_address')->nullable();
            $table->string('send_txid')->nullable();
            $table->string('time_created')->nullable();
            $table->longText('note')->nullable();
            $table->foreignId('liquidaction_id')->constrained();
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
        Schema::dropIfExists('coinpayment_withdrawals');
    }
};
