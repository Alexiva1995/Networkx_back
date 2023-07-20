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
        Schema::table('inversions', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->after('status')->comment('0 - Matrix inicial , 1 - Matrix 200 USD , 2 - Matrix 2000 USD , 3 - Mineria');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inversions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
