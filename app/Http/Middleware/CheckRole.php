<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // chequeamos que el usuario este logueado
        if(is_null($request->user())){
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }
        // obtenemos los roles pasados por parametro 
        // a partir del 3er parametro (el 1er parametro es $request y el 2do $next)
        $roles = array_slice( func_get_args(), 2 );
        if(!$request->user()->hasRole($roles)){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        return $next($request);
    }
}
