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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('country');
            $table->string('document_id'); // Cambiado a tipo string
            $table->string('postal_code');
            $table->string('phone_number');
            $table->unsignedTinyInteger('status')->default(0)->comment('0: Solicitado, 1: Enviado, 2: Entregado');
            $table->string('state');
            $table->string('street');
            $table->string('department');
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
        Schema::dropIfExists('products');
    }
};
