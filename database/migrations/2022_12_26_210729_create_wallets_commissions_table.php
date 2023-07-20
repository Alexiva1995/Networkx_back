<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unsigned()->comment('user to whom the wallet belongs');
            $table->foreign('user_id')->nullable()->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('buyer_id')->nullable()->unsigned()->nullable();
            $table->foreign('buyer_id')->references('id')->on('users')->onUpdate('cascade')->nullable();
            $table->bigInteger('order_id')->nullable()->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->nullable();
            $table->bigInteger('transfer_id')->nullable()->unsigned()->nullable()->comment('wallet id to which it was transferred');
            $table->foreign('transfer_id')->references('id')->on('wallets_commissions')->onUpdate('cascade')->nullable();
            $table->bigInteger('level');
            $table->string('description',100);
            $table->foreignId('membership_id')->nullable()->constrained('projects')->comment('investment which produces this wallet');
            $table->double('amount')->nullable()->comment('amount for display');
            $table->double('amount_retired')->nullable()->comment('amount retired for calculations');
            $table->double('amount_available')->nullable()->comment('amount for calculations');
            $table->double('amount_last_liquidation')->nullable()->comment('amount last settlement');
            $table->tinyInteger('type')->default(0)->comment('0 - matrix 20 , 1 - matrix 200, 2 - matrix 2000');
            $table->bigInteger('liquidation_id')->unsigned()->nullable();
            $table->tinyInteger('liquidado')->comment('0 - no , 1 - si')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0 - Available, 1 - Requested, 2 - Paid, 3 - Voided, 4 - Pending');
            $table->tinyInteger('available_withdraw')->default(0)->comment('0 - No Disponible, 1 - Disponible');
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
        Schema::dropIfExists('wallets_commissions');
    }
}
