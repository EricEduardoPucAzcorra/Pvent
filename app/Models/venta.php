<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $primaryKey='id_venta';

    protected $incremeting=true;

    public $timestamps=true;

    protected $fillable=[
        'id_cliente',
        'id_usuario',
        'tipo_documento',
        'serie_comprobante',
        'num_documento',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario'); 
    }

}
