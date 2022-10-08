<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $primaryKey = 'id_articulo';

    public $with=['categoria'];

    protected $fillable=[
        'id_categoria',
        'codigo',
        'nombre',
        'precio_venta',
        'stock',
        'descripcion',
        'condicion'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }


}
