<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Persona;
use App\Models\Proveedor;
use Exception;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $proveedores = Proveedor::
            join('personas', 'proveedores.id_proveedor', '=' , 'personas.id_persona')
            ->select('personas.id_persona', 'personas.nombre', 'personas.tipo_documento'
            ,'personas.num_documento','personas.direccion', 'personas.telefono',
            'personas.email', 'proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id_persona','DESC')->paginate(3);

        }else{
            $proveedores = Proveedor::join('personas', 'proveedores.id_proveedor', '=' , 
            'personas.id_persona')->select('personas.id_persona', 'personas.nombre', 'personas.tipo_documento',
            'personas.num_documento','personas.direccion', 'personas.telefono',
            'personas.email', 'proveedores.contacto','proveedores.telefono_contacto')
            -> where('personas.'.$criterio, 'like', '%' .$buscar. '%')
            ->orderBy('personas.id_persona','DESC')->paginate(3);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page'     => $proveedores->perPage(),
                'last_page'    => $proveedores->lastPage(),
                'from'         => $proveedores->firstItem(),
                'to'           => $proveedores->lastItem(),
            ],
            'proveedores'=> $proveedores
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

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->get('contacto');
            $proveedor->telefono_contacto = $request->get('telefono_contacto');
            $proveedor->id_proveedor = $persona->id_persona;
            $proveedor->save();

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

            //buscar id del proveedor
            $proveedor  = Proveedor::findOrFail($request->id_proveedor);
            
            $persona  = Persona::findOrFail($proveedor->id_proveedor);
            
            //ACTUALIZO
            $persona->nombre = $request->get('nombre');
            $persona->tipo_documento = $request->get('tipo_documento');
            $persona->num_documento = $request['num_documento'];
            $persona->direccion = $request['direccion'];
            $persona->telefono = $request->get('telefono');
            $persona->email = $request->get('email');
            $persona->update();
            
            //ACTUALIZO
            $proveedor->contacto = $request->get('contacto');
            $proveedor->telefono_contacto = $request->get('telefono_contacto');
            $proveedor->update();

            DB::commit();
        } catch(Exception $e){
            //muestro errors
            DB::rollBack();
        }
    }
    // Select proveedor vue select

    public function selectProveedor(Request $request){
        $filtro = $request->filtro;

        $proveedores = Proveedor::join('personas', 'proveedores.id_proveedor', '=', 'personas.id_persona')
                                ->where('personas.nombre', 'LIKE', '%'. $filtro .'%')
                                ->orWhere('personas.num_documento', 'Like', '%'. $filtro .'%')
                                ->select('personas.id_persona', 'personas.nombre', 'personas.num_documento')
                                ->orderBy('personas.nombre', 'ASC')->get();


        return ['proveedores' => $proveedores];

    }
}
