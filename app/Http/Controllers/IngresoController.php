<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ingreso;

use App\Models\DetalleIngreso;

use \DB;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;



class IngresoController extends Controller
{
    public function index(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $ingresos = Ingreso::
            join('personas', 'ingresos.id_proveedor', '=' , 'personas.id_persona')
            ->join('users', 'ingresos.id_usuario','=','users.id_usuario')
            ->select('ingresos.id_ingreso', 'ingresos.tipo_documento', 'ingresos.serie_comprobante'
            ,'ingresos.num_comprobante','ingresos.fecha_hora', 'ingresos.impuesto',
            'ingresos.total', 'ingresos.estado', 'personas.nombre', 'users.usuario')
            ->orderBy('ingresos.id_ingreso','DESC')->paginate(3);

        }else{
            $ingresos = Ingreso::join('personas', 'ingresos.id_proveedor', '=' , 'personas.id_persona')
            ->join('users', 'ingresos.id_usuario','=','users.id_usuario')
            ->select('ingresos.id_ingreso', 'ingresos.tipo_documento', 'ingresos.serie_comprobante'
            ,'ingresos.num_comprobante','ingresos.fecha_hora', 'ingresos.impuesto',
            'ingresos.total', 'ingresos.estado', 'personas.nombre', 'users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%' .$buscar. '%')
            ->orderBy('ingresos.id_ingreso','DESC')->paginate(3);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos'=> $ingresos
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
    
        try{

            //hagotransaccion
            DB::beginTransaction();

            $hora_fecha = Carbon::now('America/Mexico_City');

            $ingreso = new Ingreso();
            $ingreso->id_proveedor = $request['id_proveedor'];
            $ingreso->id_usuario = Auth::user()->id_usuario;
            $ingreso->tipo_documento = $request['tipo_documento'];
            $ingreso->num_comprobante = $request['num_comprobante'];
            $ingreso->serie_comprobante = $request['serie_comprobante'];
            $ingreso->fecha_hora = $hora_fecha->toDateString();
            $ingreso->impuesto = $request['impuesto'];
            $ingreso->total = $request['total'];
            $ingreso->estado = 'Registrado';
            $ingreso->save();

            $detalles = $request->data;

            foreach($detalles as $ep=>$det){
                $detalle = new DetalleIngreso();
                $detalle->id_ingreso = $ingreso->id_ingreso;
                $detalle->id_articulo = $det['id_articulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }

            DB::commit();
        } catch(Exception $e){
            //muestro errors
            DB::rollBack();
        }
      
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
          $ingreso = Ingreso::findOrFail($request->id_ingreso);
          $ingreso->estado = 'Anulado';
          $ingreso->update();
    }

    public function cabecera(Request $request){
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $id_ingreso = $request->id_ingreso;
      
        $ingreso = Ingreso::
        join('personas', 'ingresos.id_proveedor', '=' , 'personas.id_persona')
        ->join('users', 'ingresos.id_usuario','=','users.id_usuario')
        ->select('ingresos.id_ingreso', 'ingresos.tipo_documento', 'ingresos.serie_comprobante'
        ,'ingresos.num_comprobante','ingresos.fecha_hora', 'ingresos.impuesto',
        'ingresos.total', 'ingresos.estado', 'personas.nombre', 'users.usuario')
        ->where('ingresos.id_ingreso','=', $id_ingreso)
        ->orderBy('ingresos.id_ingreso','DESC')->take(1)->get();
    
        //retornar propiedades
        return [
            'ingreso'=> $ingreso
        ];
    }

    public function detalles(Request $request){
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $id_ingreso = $request->id_ingreso;
      
        $Detalleingresos = DetalleIngreso::
        join('articulos', 'detalle_ingresos.id_articulo', '=' , 'articulos.id_articulo')
        ->select('detalle_ingresos.cantidad', 'detalle_ingresos.precio', 'articulos.nombre as articulo')
        ->where('detalle_ingresos.id_ingreso','=', $id_ingreso)
        ->orderBy('detalle_ingresos.id_detalleI','DESC')->get();
    
        //retornar propiedades
        return [
            'Detalleingresos'=> $Detalleingresos
        ];
    }
    

}
