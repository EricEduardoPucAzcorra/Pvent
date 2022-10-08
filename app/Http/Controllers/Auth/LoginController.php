<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
//clases de login
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use App\Models\Rol;


class LoginController extends Controller
{
    // public function showLoginForm(){
    //     return view('auth.login');
    // }

    public function vistalogin(){
        return view('auth.login');
    }

    public function login(Request $request){
        //validar con if
        $this->validarlogin($request);

        if(Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password, 'condicion' => 1])){
            //si cumple me redirige a la vista 
            // $id_rol = Auth::user()->id_rol;

            // $rol = Rol::find('id_rol', $id_rol );

            return redirect()->route('principal');
           
        }
        //regresar atras, reorna error, y permanece imput llenos
        return back()->withErrors(['usuario' => trans('auth.failed')])->withInput(request(['usuario']));
        

    }
    //metodo de validacion
    protected function validarlogin(Request $request){
        //valido
        $this->validate($request, [ 
            'usuario' => 'required|string',
            'password'=> 'required|string'
        ]); 
    }

    //cerrar session

    public function salir(Request $request){

        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');

    }
}
