<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_venta')->unsigned();
            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
            $table->integer('id_articulo')->unsigned();
            $table->foreign('id_articulo')->references('id_articulo')->on('articulos'); 
            $table->integer('cantidad');
            $table->float('precio',11,2);
            $table->float('descuento',11,2);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ventas');
    }
}
