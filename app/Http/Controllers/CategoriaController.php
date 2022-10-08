<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categoria;

use Illuminate\Support\Facades\DB;


class CategoriaController extends Controller
{
    //metodo lo que permite traer los datos activos   
    public function select_cat(Request $request){
        //filtro las categorias que su estatus sea 1
        $categorias = Categoria::where('condicion','=','1')->select('id_categoria', 'nombre')
        ->orderBy('nombre','asc')->get();

        //retorno objeto

        return ['categorias' => $categorias];
    }
    public function index(Request $request)
    {
        //seguridad
        if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $categorias = Categoria::orderBy('id_categoria','DESC')->paginate(10);

        }else{
            $categorias = Categoria::where($criterio, 'like','%' . $buscar . '%')
            ->orderBy('id_categoria','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page'     => $categorias->perPage(),
                'last_page'    => $categorias->lastPage(),
                'from'         => $categorias->firstItem(),
                'to'           => $categorias->lastItem(),
            ],
            'categorias'=> $categorias
        ];
    }
 
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id_categoria);
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->condicion = '1';
        $categoria->update();
    }

    public function activar(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id_categoria);
        $categoria->condicion = '1';
        $categoria->update();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
          $categoria = Categoria::findOrFail($request->id_categoria);
          $categoria->condicion = '0';
          $categoria->update();
    }
 
}
