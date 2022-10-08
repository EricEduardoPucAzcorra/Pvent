<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id_ingreso');

            $table->integer('id_proveedor')->unsigned();
            //relacion
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores');


            $table->integer('id_usuario')->unsigned();
            //relacion
            $table->foreign('id_usuario')->references('id_usuario')->on('users');

            $table->string('tipo_documento',20);

            $table->string('serie_comprobante',7)->nullable();

            $table->string('num_comprobante');

            $table->dateTime('fecha_hora');

            $table->float('impuesto');
            
            $table->float('total');

            $table->string('estado', 20);

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
        Schema::dropIfExists('ingresos');
    }
}
