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
        Schema::create('market_purchaseds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders');
            $table->unsignedBigInteger('cyborg_id');
            $table->tinyInteger('level')->default(0)->comment('El nivel de la matrix o cyborg');
            $table->timestamps();
            $table->foreign('cyborg_id')->references('id')->on('market')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_purchaseds');
    }
};
