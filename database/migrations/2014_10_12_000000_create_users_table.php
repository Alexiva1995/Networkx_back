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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('last_name')->nullable();
            // $table->string('password')->nullable();
            // $table->foreignId('countrie_id')
            //       ->nullable()
            //       ->constrained('countries');
            $table->timestamp('code_verified_at')->nullable();
            $table->integer('status_change')->nullable();
            $table->integer('kyc')
                   ->nullable()
                   ->comment('0 - En espera, 1 - Verificado, 2 - Cancelado');

            $table->longText('wallet')->nullable();
            $table->string('profile_picture')->nullable();
            $table->foreignId('buyer_id')->nullable()->references('id')->on('users')->comment('ID del usuario patrocinador');
            $table->foreignId('prefix_id')->nullable()->constrained('prefixes')->comment('el id del prefijo del tlf');
            $table->bigInteger('binary_id')->default(1)->comment('ID del usuario binario')->nullable();
            $table->enum('binary_side', ['L', 'R'])->default('L')->comment('Permite saber porque lado va a registrar a un nuevo usuario');
            $table->string('user_name')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('admin', [0, 1])->default(0)->comment('0 - Usuario, 1 - Administrador');
            $table->enum('status', [0, 1, 2])->default(1)->comment('0 - inactivo, 1 - activo, 2 - eliminado');
            $table->string('phone')->nullable();
            $table->tinyInteger('matrix_level')->default(0);
            $table->string('activar_2fact')->nullable();
            $table->string('token_auth')->nullable();
            $table->string('code_security', 255)->nullable()->onUpdate();
            $table->text('token_jwt')->nullable();
            $table->string('app_mode')->default('light')->comment('El modo de la app si dark o light');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
