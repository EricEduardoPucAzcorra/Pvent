<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id_venta');
            $table->integer('id_cliente')->unsigned();
            $table->foreign('id_cliente')->references('id_persona')->on('personas'); 
            $table->integer('id_usuario')->unsigned();
            $table->foreign('id_usuario')->references('id_usuario')->on('users');
            $table->string('tipo_documento',20);
            $table->string('serie_comprobante',7)->nullable();
            $table->string('num_documento',10);
            $table->dateTime('fecha_hora');
            $table->float('impuesto',11,2);
            $table->float('total',11,2);
            $table->string('estado');
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
        Schema::dropIfExists('ventas');
    }
}
