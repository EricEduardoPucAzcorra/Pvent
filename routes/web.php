<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\VentaController;

// Route::get('/', function () {
//     return view('welcome');
// });


// grupo middleware

// Route::middleware(['web'])->group(function () {
//     //
// });

//si no esta autenticado tendra acceso a las siguientes rutas del login 
Route::group(['middleware'=>['guest']], function(){
    //rutas del login
    Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'vistalogin'])->name('/');

    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

});

//usuario que este autentica 
Route::middleware(['auth'])->group(function () {
    
    Route::get('/vue', function () {
        return view('index');
    });

    Route::get('/principal', function () {
        return view('contenido.contenido');
    })->name('principal');
    

    //usuario que este autenticado como almacenero
    Route::middleware(['Almacenero'])->group(function () {
    
        //rutas  controller Categoria
        Route::get('/categoria', [CategoriaController::class, 'index']);
        Route::post('/categoria/registrar', [CategoriaController::class, 'store']);
        Route::put('/categoria/actualizar', [CategoriaController::class, 'update']);
        Route::put('/categoria/activar', [CategoriaController::class, 'activar']);
        Route::put('/categoria/desactivar', [CategoriaController::class, 'desactivar']);
        Route::get('/categoria/activo', [CategoriaController::class, 'select_cat']);

        //rutas controller Articulo
        Route::get('/articulo', [ArticuloController::class, 'index']);
        Route::post('/articulo/registrar', [ArticuloController::class, 'store']);
        Route::put('/articulo/actualizar', [ArticuloController::class, 'update']);
        Route::put('/articulo/activar', [ArticuloController::class, 'activar']);
        Route::put('/articulo/desactivar', [ArticuloController::class, 'desactivar']);
        Route::get('/articulo/buscar', [ArticuloController::class, 'buscarArticulo']);
        Route::get('/articulo/listado', [ArticuloController::class, 'listarArticulos']);
        // Route::get('/articulos', function () {
        //     return App\Models\Articulo::all();
        // });

        //controlller de proveedores
        Route::get('/proveedor', [ProveedorController::class, 'index']);
        Route::post('/proveedor/registrar', [ProveedorController::class, 'store']);
        Route::put('/proveedor/actualizar', [ProveedorController::class, 'update']);
        Route::get('/proveedor/select', [ProveedorController::class, 'selectProveedor']);

       //ingresos
       Route::get('/ingresos', [IngresoController::class, 'index']);
       Route::post('/ingreso/registrar', [IngresoController::class, 'store']);
       Route::put('/ingreso/anular', [IngresoController::class, 'desactivar']);
       Route::get('/ingresos/cabecera', [IngresoController::class, 'cabecera']);
       Route::get('/ingresos/detalles', [IngresoController::class, 'detalles']);


    });

    //middleware del usuario que este autenticado como vendendor
    Route::middleware(['Vendedor'])->group(function () {
        
        //rutas conteoller Persona Ciiente 
        Route::get('/cliente', [ClienteController::class, 'index']);
        Route::post('/cliente/registrar', [ClienteController::class, 'store']);
        Route::put('/cliente/actualizar', [ClienteController::class, 'update']);
        Route::get('/cliente/select', [ClienteController::class, 'selectCliente']);
         //ventas
        Route::get('/ventas', [VentaController::class,'index']);
        Route::post('/ventas/registrar', [VentaController::class, 'store']);
        Route::put('/ventas/anular', [VentaController::class, 'desactivar']);
        Route::get('/ventas/cabecera', [VentaController::class, 'cabecera']);
        Route::get('/ventas/detalles', [VentaController::class, 'detalles']);

        Route::get('/articulo/buscarVenta', [ArticuloController::class, 'buscarArticuloVenta']);
        Route::get('/articulo/listadoVenta', [ArticuloController::class, 'listarArticulosVenta']);
    });

  
  
    //middleware de usuario que este autenticado como admnistrador
    Route::middleware(['Administrador'])->group(function () {
        
        //rutas  controller Categoria
        Route::get('/categoria', [CategoriaController::class, 'index']);
        Route::post('/categoria/registrar', [CategoriaController::class, 'store']);
        Route::put('/categoria/actualizar', [CategoriaController::class, 'update']);
        Route::put('/categoria/activar', [CategoriaController::class, 'activar']);
        Route::put('/categoria/desactivar', [CategoriaController::class, 'desactivar']);
        Route::get('/categoria/activo', [CategoriaController::class, 'select_cat']);

        //rutas controller Articulo
        Route::get('/articulo', [ArticuloController::class, 'index']);
        Route::post('/articulo/registrar', [ArticuloController::class, 'store']);
        Route::put('/articulo/actualizar', [ArticuloController::class, 'update']);
        Route::put('/articulo/activar', [ArticuloController::class, 'activar']);
        Route::put('/articulo/desactivar', [ArticuloController::class, 'desactivar']);
        Route::get('/articulo/buscar', [ArticuloController::class, 'buscarArticulo']);
        Route::get('/articulo/listado', [ArticuloController::class, 'listarArticulos']);
        Route::get('/articulo/buscarVenta', [ArticuloController::class, 'buscarArticuloVenta']);
        Route::get('/articulo/listadoVenta', [ArticuloController::class, 'listarArticulosVenta']);
        // Route::get('/articulos', function () {
        //     return App\Models\Articulo::all();
        // });

        //controlller de proveedores
        Route::get('/proveedor', [ProveedorController::class, 'index']);
        Route::post('/proveedor/registrar', [ProveedorController::class, 'store']);
        Route::put('/proveedor/actualizar', [ProveedorController::class, 'update']);
        Route::get('/proveedor/select', [ProveedorController::class, 'selectProveedor']);
         //ingresos
        Route::get('/ingresos', [IngresoController::class, 'index']);
        Route::post('/ingreso/registrar', [IngresoController::class, 'store']);
        Route::put('/ingreso/anular', [IngresoController::class, 'desactivar']);
        Route::get('/ingresos/cabecera', [IngresoController::class, 'cabecera']);
        Route::get('/ingresos/detalles', [IngresoController::class, 'detalles']);

        //ventas

        Route::get('/ventas', [VentaController::class,'index']);
         Route::post('/ventas/registrar', [VentaController::class, 'store']);
        Route::put('/ventas/anular', [VentaController::class, 'desactivar']);
        Route::get('/ventas/cabecera', [VentaController::class, 'cabecera']);
        Route::get('/ventas/detalles', [VentaController::class, 'detalles']);

         //rutas conteoller Persona Ciiente 
         Route::get('/cliente', [ClienteController::class, 'index']);
         Route::post('/cliente/registrar', [ClienteController::class, 'store']);
         Route::put('/cliente/actualizar', [ClienteController::class, 'update']);
         Route::get('/cliente/select', [ClienteController::class, 'selectCliente']);
        ///roles
        Route::get('/roles', [RolController::class, 'index']);
        Route::get('/select_rol', [RolController::class, 'select_rol']);
        
        //usuarios
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users/registrar', [UserController::class, 'store']);
        Route::put('/users/actualizar', [UserController::class, 'update']);
        Route::put('/users/activar', [UserController::class, 'activar']);
        Route::put('/users/desactivar', [UserController::class, 'desactivar']);

    });
    
    //ruta cerrar session
    Route::post('/salir', [App\Http\Controllers\Auth\LoginController::class, 'salir'])->name('salir');

});

//prueba alumnos
Route::get('/alumnos', [AlumnosController::class, 'index']);
Route::post('/alumnos/registrar', [AlumnosController::class, 'store']);
Route::put('/alumnos/actualizar', [AlumnosController::class, 'update']);
Route::put('/alumnos/desactivar', [AlumnosController::class, 'desactivar']);
Route::put('/alumnos/activar', [AlumnosController::class, 'activar']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


