<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIfBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->lock) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('error', 'Tu cuenta está bloqueada.');
        }

        return $next($request);
    }
}
