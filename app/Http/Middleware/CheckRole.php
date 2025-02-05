<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        switch ($permission) {
            case 'administrador':
                if (Auth::check() && Auth::user()->can($permission)) {
                    return $next($request);
                }
                break;
            case 'asistente':
                if (Auth::check() && Auth::user()->can($permission)) {
                    return $next($request);
                }
                break;
            case 'contratista':
                if (Auth::check() && Auth::user()->can($permission)) {
                    return $next($request);
                }
                break;
        }

        return redirect()->route('dashboard')->with('mensaje', 'No tienes acceso a esta página.');
    }
}
