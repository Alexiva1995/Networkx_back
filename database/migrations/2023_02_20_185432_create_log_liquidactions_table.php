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
        Schema::create('log_liquidactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('liquidation_id')->unsigned();
            $table->foreign('liquidation_id')->references('id')->on('liquidactions')->onUpdate('cascade')->onDelete('cascade');
            $table->string('comentario')->nullable();
            $table->string('accion');
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
        Schema::dropIfExists('log_liquidactions');
    }
};
