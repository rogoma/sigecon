<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Enviar_alertas;

class HomeController extends Controller
{
    /**
     * Display index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // En caso de no estar logueado redirigimos a login
        if(is_null($request->user())){
            return redirect()->route('login');
        }
        return view('home');
    }

    /**
     * Display login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        return view('login');
    }

    /**
     * Display login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLogin(Request $request){
        // chequeamos los campos del formulario enviados
        $rules = array(
            'document' => ['required', 'numeric', 'max:999999999999999'],   // se admite hasta 15 cifras
            'password' => ['required', 'string', 'max:100'],
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos si los datos enviados corresponden a algun usuario
        $credentials = $request->only('document', 'password');
        if(Auth::attempt($credentials)){    // intentamos iniciar sesion

            // COMPROBAMOS SI USUARIO ESTÁ ACTIVO (1)/INACTIVO (2)
            $user = User::where('document', $request->input('document'))
            ->where('state', '=', 1)
            ->get();

            if($user->count() == 0){
                $validator->errors()->add('bad_credentials', 'Usuario Inactivo');
                return back()->withErrors($validator)->withInput();
            }

            // Migración perezosa: si la contraseña todavía tiene el hash SHA1 legado,
            // la regeneramos con bcrypt ahora que sabemos la contraseña en texto plano.
            $authUser = Auth::user();
            if (Hash::needsRehash($authUser->password)) {
                $authUser->password = Hash::make($request->input('password'));
                $authUser->save();
            }

            // En caso de inicio de sesión exitoso retornamos a la ruta
            // intentada previamente por el usuario (en caso de no haber ruta intentada redirigimos a home)
            return redirect()->intended('/');
            // Mail::to('rogoma700@gmail.com')->send(new Enviar_alertas($contenido));

        }else{
            $validator->errors()->add('bad_credentials', 'Credenciales incorrectas.');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        Auth::logout();
        return redirect('/login');
    }
}
