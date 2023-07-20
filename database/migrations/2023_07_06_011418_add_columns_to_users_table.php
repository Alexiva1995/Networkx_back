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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('type_matrix')->nullable()->comment('0 - matrix 1, 1 - matrix 2, 2 - matrix 3, 3 - matrix 4, 4 - matrix 5');
            $table->tinyInteger('type_service')->nullable()->comment('0 - product, 2 - services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type_matrix');
            $table->dropColumn('type_services');
        });
    }
};
