<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Persona;
// use App\Models\Proveedor;

use Illuminate\Support\Facades\DB;

use Exception;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $usuarios = User::
            join('personas', 'users.id_usuario', '=' , 'personas.id_persona')
            ->join('roles', 'users.id_rol','=','roles.id_rol')
            ->select('personas.id_persona', 'personas.nombre', 'personas.tipo_documento'
            ,'personas.num_documento','personas.direccion', 'personas.telefono',
            'personas.email', 'users.usuario','users.password','users.condicion','roles.id_rol','roles.nombre as nombre_rol')
            ->orderBy('personas.id_persona','DESC')->paginate(3);

        }else{
            $usuarios = User::join('personas', 'users.id_usuario', '=' , 'personas.id_persona')
            ->join('roles', 'users.id_rol','=','roles.id_rol')
            ->select('personas.id_persona', 'personas.nombre', 'personas.tipo_documento'
            ,'personas.num_documento', 'personas.direccion', 'personas.telefono',
            'personas.email', 'users.usuario', 'users.password', 'users.condicion', 'roles.id_rol','roles.nombre as nombre_rol')
            ->where('personas.'.$criterio, 'like', '%' .$buscar. '%')
            ->orderBy('personas.id_persona','DESC')->paginate(3);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page'     => $usuarios->perPage(),
                'last_page'    => $usuarios->lastPage(),
                'from'         => $usuarios->firstItem(),
                'to'           => $usuarios->lastItem(),
            ],
            'usuarios'=> $usuarios
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
    
        try{
            //hagotransaccion
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->get('nombre');
            $persona->tipo_documento = $request->get('tipo_documento');
            $persona->num_documento = $request['num_documento'];
            $persona->direccion = $request['direccion'];
            $persona->telefono = $request->get('telefono');
            $persona->email = $request->get('email');
            $persona->save();

         


            $user = new User();
            $user->usuario = $request->get('usuario');
            $user->password = bcrypt($request->get('password'));
            $user->condicion = '1';
            $user->id_rol = $request->get('id_rol');
            $user->id_usuario = $persona->id_persona;
            $user->save();

            DB::commit();
        } catch(Exception $e){
            //muestro errors
            DB::rollBack();
        }
      
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
    
        try{
            //hagotransaccion
            DB::beginTransaction();

            //buscar id del usuario
            $usuario  = User::findOrFail($request->id_usuario);
            
            $persona  = Persona::findOrFail($usuario->id_usuario);
            
            //ACTUALIZO
            $persona->nombre = $request->get('nombre');
            $persona->tipo_documento = $request->get('tipo_documento');
            $persona->num_documento = $request['num_documento'];
            $persona->direccion = $request['direccion'];
            $persona->telefono = $request->get('telefono');
            $persona->email = $request->get('email');
            $persona->update();
            
            //ACTUALIZO usuario
            $usuario->usuario = $request->get('usuario');
            $usuario->password = bcrypt($request->get('password'));
            $usuario->condicion = '1';
            $usuario->id_rol = $request->get('id_rol');
            $usuario->update();

            DB::commit();
        } catch(Exception $e){
            //muestro errors
            DB::rollBack();
        }
    }



    public function activar(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id_usuario);
        $user->condicion = '1';
        $user->update();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
          $user = User::findOrFail($request->id_usuario);
          $user->condicion = '0';
          $user->update();
    }
}
