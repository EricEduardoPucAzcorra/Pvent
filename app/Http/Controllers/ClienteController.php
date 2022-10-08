<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Persona;

class ClienteController extends Controller
{
    //

    public function index(Request $request)
    {
        //seguridad
        if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $personas = Persona::orderBy('id_persona','DESC')->paginate(10);

        }else{
            $personas = Persona::where($criterio, 'like','%' . $buscar . '%')
            ->orderBy('id_persona','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas'=> $personas
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request['num_documento'];
        $persona->direccion = $request['direccion'];
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id_persona);
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request['num_documento'];
        $persona->direccion = $request['direccion'];
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->update();
    }

    public function selectCliente(Request $request)
    {
        $filtro = $request->filtro;

        $clientes = Persona::where('nombre', 'LIKE', '%'. $filtro .'%')
                                ->orWhere('num_documento', 'Like', '%'. $filtro .'%')
                                ->select('id_persona', 'nombre', 'num_documento')
                                ->orderBy('nombre', 'ASC')->get();


        return ['clientes' => $clientes];
    }

}
