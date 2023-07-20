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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->tinyInteger('categories')->default(0)->comment('0 - Ayuda, 1 - Soporte técnico, 2 - Corrección de datos, 3 - Bonos, 4 - Inversión total');
            // $table->boolean('priority', [0, 1, 2])->default(2)->comment('0 - Alta, 1 - Media, 2 - Baja');
            $table->tinyInteger('status')->default(0)->comment('0 - Abierto, 1 - Cerrado');
            $table->text('subject');
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
        Schema::dropIfExists('tickets');
    }
};
