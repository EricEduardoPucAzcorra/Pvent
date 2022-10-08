<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_usuario')->unsigned();
            //relacion
            $table->foreign('id_usuario')->references('id_persona')->on('personas')->onDelete('cascade');

            $table->string('usuario')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('condicion')->default(1);
            //relacion con rol
            $table->integer('id_rol')->unsigned(1);

            $table->foreign('id_rol')->references('id_rol')->on('roles');

            //$table->rememberToken();
            //$table->timestamps();
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
}
