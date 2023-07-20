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
        Schema::table('market_purchaseds', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->comment('1 - matrix 20, 2 matrix 200, 3 matrix 2000')->after('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_purchaseds', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
