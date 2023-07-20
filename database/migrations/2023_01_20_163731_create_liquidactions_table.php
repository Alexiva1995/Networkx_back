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
        Schema::create('liquidactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            // $table->bigInteger('membership_id')->unsigned();
            // $table->foreign('membership_id')->nullable()->references('id')->on('memberships')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->double('total');
            $table->double('monto_bruto');
            $table->double('feed');
            $table->string('hash')->nullable();
            $table->string('processId')->nullable();
            $table->string('secret')->nullable();
            $table->longText('wallet_used')->nullable();
            $table->string('code_correo')->nullable();
            $table->dateTime('fecha_code')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 - Comisiones, 1 - Capital');
            $table->tinyInteger('status')->default(0)->comment('0 - Pending, 1 - Requested, 2 - Approbed, 3 - Rejected');
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
        Schema::dropIfExists('liquidactions');
    }
};
