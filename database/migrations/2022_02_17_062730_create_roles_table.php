<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id_rol');
            $table->string('nombre',50)->unique();
            $table->string('descripcion',100)->nullable();
            $table->boolean('condicion')->default(1);
            
        });
        //registros de roles estaticos
        DB::table('roles')->insert(array('id_rol'=>'1', 'nombre'=>'Administrador', 'descripcion'=>'Gestionador'));
        DB::table('roles')->insert(array('id_rol'=>'2', 'nombre'=>'Vendedor', 'descripcion'=>'Personal de ventas'));
        DB::table('roles')->insert(array('id_rol'=>'3', 'nombre'=>'Almacenero', 'descripcion'=>'Gestionador de almacenes'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
