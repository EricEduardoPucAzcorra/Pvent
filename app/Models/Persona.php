<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $primaryKey = 'id_persona';

    public $fillable=[
        'nombre',
        'tipo_documento',
        'num_documento',
        'direccion',
        'telefono',
        'email'
    ];
    //relacion con la tabla de proveedores
    public function proveedor()
    {
       return $this->hasOne(Proveedor::class);
    }

    //una persona solo se relaciona con un solo usuario
    public function usuario()
    {
       return $this->hasOne(User::class);
    }

}
