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
        Schema::create('message_tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('support_id')->unsigned();
            $table->foreign('support_id')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onUpdate('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->enum('type', [0, 1])->default(0)->comment('0 - User, 1 - Admin');
            $table->longtext('message');
            $table->longText('image')->nullable();
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
        Schema::dropIfExists('message_tickets');
    }
};
