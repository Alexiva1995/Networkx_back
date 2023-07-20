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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->bigInteger('coupon_id')->unsigned()->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onUpdate('cascade')->nullable();
            $table->integer('amount');
            $table->string('hash')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['inicio', 'renovacion']);
            $table->enum('status', [0, 1, 2, 3])->default(0)->comment('0 - En Espera, 1 - Completada, 2 - Parcialmente Pagado, 3 - Rechazado');
            $table->foreignId('membership_packages_id')->nullable()->constrained('package_memberships');
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
        Schema::dropIfExists('orders');
    }
};
