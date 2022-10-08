<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    
    protected $table='roles';

    protected $primaryKey='id_rol';

    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'descripcion',
        'condicion'
    ];
    //los roles pueden ser para varios usuarios
    public function users(){
        return $this->hasMany(User::class); 
    }
}
