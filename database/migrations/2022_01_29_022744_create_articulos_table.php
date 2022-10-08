<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    //tabla de productos
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id_articulo');
            $table->integer('id_categoria')->unsigned();
            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 150)->unique();
            $table->decimal('precio_venta', 11, 2);
            $table->integer('stock');
            $table->string('descripcion', 250)->nullable();
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            //relaciones
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
