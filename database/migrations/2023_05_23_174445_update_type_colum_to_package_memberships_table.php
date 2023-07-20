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
        Schema::table('package_memberships', function (Blueprint $table) {
            $table->integer('type')->comment('1 - FYT EVALUATION, 2 - FYT FAST, 3 - FYT ACCELERATED, 4 - FLASH')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_memberships', function (Blueprint $table) {
            //
        });
    }
};
