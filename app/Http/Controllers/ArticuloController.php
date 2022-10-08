<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Articulo;

class ArticuloController extends Controller
{
    //

    public function index(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        //inputs request busqueda
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){

            //se hace cuando hay relaciones 

            //el join lo relaciono con categorias donde igual que la id del tabla articulos sera igual a la de categorias
            $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
            //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
            ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
            ->orderBy('articulos.id_articulo','DESC')->paginate(10);

        }else{

             //el join lo relaciono con categorias donde la id del tabla articulos sera igual a la de categorias
             $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
             //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
             ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
             ->where('articulos.'.$criterio, 'like','%' . $buscar . '%')
             ->orderBy('articulos.id_articulo','DESC')->paginate(10);

            //$articulos = Articulo::where($criterio, 'like','%' . $buscar . '%')->orderBy('id','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page'     => $articulos->perPage(),
                'last_page'    => $articulos->lastPage(),
                'from'         => $articulos->firstItem(),
                'to'           => $articulos->lastItem(),
            ],
            'articulos'=> $articulos
        ];
    }


    
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $articulo = new Articulo();
        $articulo->id_categoria = $request->get('id_categoria');
        $articulo->codigo = $request->get('codigo');
        $articulo->nombre = $request->get('nombre');
        $articulo->precio_venta = $request->get('precio_venta');
        $articulo->stock = $request->get('stock');
        $articulo->descripcion = $request->get('descripcion');
        $articulo->condicion = '1';
        $articulo->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id_articulo);
        $articulo->id_categoria = $request->get('id_categoria');
        $articulo->codigo = $request->get('codigo');
        $articulo->nombre = $request->get('nombre');
        $articulo->precio_venta = $request->get('precio_venta');
        $articulo->stock = $request->get('stock');
        $articulo->descripcion = $request->get('descripcion');
        $articulo->condicion = '1';
        $articulo->update();
    }

    public function activar(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id_articulo);
        $articulo->condicion = '1';
        $articulo->update();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id_articulo);
        $articulo->condicion = '0';
        $articulo->update();
    }
    public function buscarArticulo(Request $request){
        $filtro = $request->filtro;

        $articulos = Articulo::where('codigo','=',$filtro)
                            ->select('id_articulo','nombre')
                            ->orderBy('nombre', 'asc')
                            ->take(1)
                            ->get();

        return ['articulos' => $articulos];
    }

    public function listarArticulos(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        //inputs request busqueda
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){

            //se hace cuando hay relaciones 

            //el join lo relaciono con categorias donde igual que la id del tabla articulos sera igual a la de categorias
            $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
            //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
            ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
            ->orderBy('articulos.id_articulo','DESC')->paginate(10);

        }else{

             //el join lo relaciono con categorias donde la id del tabla articulos sera igual a la de categorias
             $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
             //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
             ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
             ->where('articulos.'.$criterio, 'like','%' . $buscar . '%')
             ->orderBy('articulos.id_articulo','DESC')->paginate(10);

            //$articulos = Articulo::where($criterio, 'like','%' . $buscar . '%')->orderBy('id','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'articulos'=> $articulos
        ];

    }
    public function buscarArticuloVenta(Request $request){
        $filtro = $request->filtro;

        $articulos = Articulo::where('codigo','=',$filtro)
                            ->select('id_articulo','nombre','stock','precio_venta')
                            ->where('stock','>','0')
                            ->orderBy('nombre', 'asc')
                            ->take(1)
                            ->get();

        return ['articulos' => $articulos];
    }
    public function listarArticulosVenta(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        //inputs request busqueda
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){

            //se hace cuando hay relaciones 

            //el join lo relaciono con categorias donde igual que la id del tabla articulos sera igual a la de categorias
            $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
            //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
            ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id_articulo','DESC')->paginate(10);

        }else{

             //el join lo relaciono con categorias donde la id del tabla articulos sera igual a la de categorias
             $articulos = Articulo::join('categorias', 'articulos.id_categoria', '=' , 'categorias.id_categoria')
             //voy a obtenrr los campos (Nota: el nombre de la categoria fue renombra por el elemnto as a nombre_cat)
             ->select('articulos.id_articulo', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_cat', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
             ->where('articulos.stock','>','0')
             ->where('articulos.'.$criterio, 'like','%' . $buscar . '%')
             
             ->orderBy('articulos.id_articulo','DESC')->paginate(10);

            //$articulos = Articulo::where($criterio, 'like','%' . $buscar . '%')->orderBy('id','DESC')->paginate(10);
        }
    
        //retornar propiedades
        return [
            'articulos'=> $articulos
        ];

    }



}
