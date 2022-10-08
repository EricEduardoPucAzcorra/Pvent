<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Rol;

class RolController extends Controller
{
   //captura rols activos
    public function select_rol(){
          //filtro las roles que su estatus sea 1
          $roles = Rol::where('condicion','=','1')->select('id_rol', 'nombre')
          ->orderBy('nombre','asc')->get();
  
          //retorno objeto
  
          return ['roles' => $roles];
    }
    
    //metodos
    public function index(Request $request)
    {
        
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $roles = Rol::orderBy('id_rol','DESC')->paginate(10);

        }else{
            $roles = Rol::where($criterio, 'like', '%' . $buscar . '%')
            ->orderBy('id_rol','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles'=> $roles
        ];
    }
}
