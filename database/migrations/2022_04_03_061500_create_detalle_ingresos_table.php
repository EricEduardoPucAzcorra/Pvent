<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->increments('id_detalleI');
            //relacion con ingresos
            $table->integer('id_ingreso')->unsigned();
            //relacion
            $table->foreign('id_ingreso')->references('id_ingreso')->on('ingresos')->onDelete('cascade');
            //relacion con productos
            $table->integer('id_articulo')->unsigned();
            //relacion
            $table->foreign('id_articulo')->references('id_articulo')->on('articulos')->onDelete('cascade');
            
            $table->integer('cantidad');

            $table->float('precio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ingresos');
    }
}
