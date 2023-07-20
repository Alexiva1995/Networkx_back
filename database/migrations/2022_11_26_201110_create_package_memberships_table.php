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
        Schema::create('package_memberships', function (Blueprint $table) {
            $table->id();
            $table->double('account');
            $table->double('amount');
            $table->enum('type', [1,2,3])->comment('1 - FYT EVALUATION, 2 - FYT FAST, 3 - FYT ACCELERATED');
            $table->text('target')->nullable();
            $table->text('min_trading_days')->nullable();
            $table->text('daily_starting_drawdown')->nullable();
            $table->text('overall_drawdown')->nullable();
            $table->text('available_Leverage')->nullable();
            $table->double('scability_plan')->nullable();
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
        Schema::dropIfExists('package_memberships');
    }
};
