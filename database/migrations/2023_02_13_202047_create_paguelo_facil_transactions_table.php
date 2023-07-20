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
        Schema::create('paguelo_facil_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('order_id')->constrained('orders');
            $table->float('amount', 10,3)->comment('our internal amount');
            $table->float('total_pay', 10,3)->nullable();
            $table->timestamp('expiration_time');
            $table->float('request_pay_amount', 10,3)->nullable();
            $table->tinyInteger('status')->default(2)->comment('0 - Failed | 1 - Success | 2 - Pending');
            $table->string('operation_code')->nullable();
            $table->string('display_num')->nullable();
            $table->string('date')->nullable();
            $table->string('operation_type')->nullable();
            $table->string('code');
            $table->string('return_url')->nullable()->comment('the invoice url');
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
        Schema::dropIfExists('paguelo_facil_transactions');
    }
};
