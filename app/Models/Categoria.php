<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $primaryKey = 'id_categoria';

    public $fillable=[
        'nombre',
        'descripcion',
        'condicion'
    ];

    public function articulos()
    {
       return $this->hasMany(Categoria::class);
    }
}
