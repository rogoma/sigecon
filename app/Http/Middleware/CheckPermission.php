<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
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
        // obtenemos los permisos pasados por parametro (a partir del 3er parametro, el 1ero es $request y el 2do $next)
        $permissions = array_slice( func_get_args(), 2 );
        if(!$request->user()->hasPermission($permissions)){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        return $next($request);
    }
}
