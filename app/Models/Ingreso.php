<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;
    protected $table = 'ingresos';

    protected $primaryKey='id_ingreso';

    // protected $incremeting=true;

    public $timestamps=true;

    protected $fillable=[
        'id_proveedor',
        'id_usuario',
        'tipo_documento',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario'); 
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor'); 
    }
}
