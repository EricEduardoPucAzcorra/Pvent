<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleventa extends Model
{
    use HasFactory;

     protected $table = 'detalle_ventas';

    protected $primaryKey='id';

    protected $incremeting=true;

    public $timestamps=false;

    protected $fillable=[
        'id_venta',
        'id_articulo',
        'cantidad',
        'precio',
        'descuento'
    ];
}
