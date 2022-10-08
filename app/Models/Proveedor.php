<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $primaryKey = 'id_proveedor';

    public $timestamps=false;

    public $fillable=[
        'id_proveedor',
        'contacto',
        'telefono_contacto'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_proveedor', 'id_persona');
    }
}
