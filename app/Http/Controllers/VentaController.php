<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \DB;

use App\Models\Venta;

use App\Models\DetalleVenta;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if($buscar==''){
            $ventas = Venta::
            join('personas', 'ventas.id_cliente', '=' , 'personas.id_persona')
            ->join('users', 'ventas.id_usuario','=','users.id_usuario')
            ->select('ventas.id_venta', 'ventas.tipo_documento', 'ventas.serie_comprobante'
            ,'ventas.num_documento','ventas.fecha_hora', 'ventas.impuesto',
            'ventas.total', 'ventas.estado', 'personas.nombre', 'users.usuario')
            ->orderBy('ventas.id_venta','DESC')->paginate(3);

        }else{
            $ventas = Venta::
            join('personas', 'ventas.id_cliente', '=' , 'personas.id_persona')
            ->join('users', 'ventas.id_usuario','=','users.id_usuario')
            ->select('ventas.id_venta', 'ventas.tipo_documento', 'ventas.serie_comprobante'
            ,'ventas.num_documento','ventas.fecha_hora', 'ventas.impuesto',
            'ventas.total', 'ventas.estado', 'personas.nombre', 'users.usuario')
            ->where('ventas.'.$criterio, 'like', '%' .$buscar. '%')
            ->orderBy('ventas.id_venta','DESC')->paginate(3);
        }
    
        //retornar propiedades
        return [
            'pagination' => [
                //propiedades de aginations
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas'=> $ventas
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
    
        try{

            //hagotransaccion
            DB::beginTransaction();

            $hora_fecha = Carbon::now('America/Mexico_City');

            $venta = new Venta();
            $venta->id_cliente = $request['id_cliente'];
            $venta->id_usuario = Auth::user()->id_usuario;
            $venta->tipo_documento = $request['tipo_documento'];
            $venta->num_documento = $request['num_documento'];
            $venta->serie_comprobante = $request['serie_comprobante'];
            $venta->fecha_hora = $hora_fecha->toDateString();
            $venta->impuesto = $request['impuesto'];
            $venta->total = $request['total'];
            $venta->estado = 'Registrado';
            $venta->save();

            $detalles = $request->data;

            foreach($detalles as $ep=>$det){
                $detalle = new DetalleVenta();
                $detalle->id_venta = $venta->id_venta;
                $detalle->id_articulo = $det['id_articulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
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
          $venta = Venta::findOrFail($request->id_venta);
          $venta->estado = 'Anulado';
          $venta->update();
    }

    public function cabecera(Request $request){
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $id_venta = $request->id_venta;
      
        $venta = Venta::
        join('personas', 'ventas.id_cliente', '=' , 'personas.id_persona')
        ->join('users', 'ventas.id_usuario','=','users.id_usuario')
        ->select('ventas.id_venta', 'ventas.tipo_documento', 'ventas.serie_comprobante'
        ,'ventas.num_documento','ventas.fecha_hora', 'ventas.impuesto',
        'ventas.total', 'ventas.estado', 'personas.nombre', 'users.usuario')
        ->where('ventas.id_venta','=', $id_venta)
        ->orderBy('ventas.id_venta','DESC')->take(1)->get();
    
        //retornar propiedades
        return [
            'venta'=> $venta
        ];
    }

    public function detalles(Request $request){
        //seguridad
        //if(!$request->ajax()) return redirect('/');
        $id_venta = $request->id_venta;
      
        $detalles = DetalleVenta::
        join('articulos', 'detalle_ventas.id_articulo', '=' , 'articulos.id_articulo')
        ->select('detalle_ventas.cantidad', 'detalle_ventas.precio', 'detalle_ventas.descuento','articulos.nombre as articulo')
        ->where('detalle_ventas.id_venta','=', $id_ingreso)
        ->orderBy('detalle_ventas.id','DESC')->get();
    
        //retornar propiedades
        return [
            'DetalleVentas'=> $detalles
        ];
    }
}
