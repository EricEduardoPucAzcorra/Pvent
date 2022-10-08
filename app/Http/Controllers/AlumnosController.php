<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alumno;

class AlumnosController extends Controller
{
 
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        //inputs request de la busqueda
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $alumnos = Alumno::orderBy('id','DESC')->paginate(10);

        }else{
            $alumnos = Alumno::where($criterio, 'like','%' . $buscar . '%')
            ->orderBy('id','DESC')->paginate(10);
        }

        //$alumnos = Alumno::paginate(5);

        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $alumnos->total(),
                'current_page' => $alumnos->currentPage(),
                'per_page'     => $alumnos->perPage(),
                'last_page'    => $alumnos->lastPage(),
                'from'         => $alumnos->firstItem(),
                'to'           => $alumnos->lastItem(),
            ],
            'alumnos'=> $alumnos
        ];

        // return $alumnos;
    }


    public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');


    // return "Noo Llego una imagen";
        $alumno = new Alumno();
        $alumno->nombre = $request->get('nombre');
        $alumno->apellidos = $request->get('apellidos');
        $alumno->edad = $request->get('edad');
        $alumno->condicion = '1';
  
        $alumno->save();
    }

    public function update(Request $request)
    {
        //
        //if(!$request->ajax()) return redirect('/');
        $alumno = Alumno::findOrFail($request->id);
        $alumno->nombre = $request->get('nombre');
        $alumno->apellidos = $request->get('apellidos');
        $alumno->edad = $request->get('edad');
        $alumno->condicion = '1';
        $alumno->update();
    }

    public  function activar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $alumno = Alumno::findOrFail($request->id);
        $alumno->condicion = '1';
        $alumno->update();
    }

    public function desactivar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $alumno = Alumno::findOrFail($request->id);
        $alumno->condicion = '0';
        $alumno->update();
    }

  
}
