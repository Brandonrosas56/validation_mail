<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
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
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convertir minutos a segundos

            if ($lastActivity && time() - $lastActivity > $sessionLifetime) {
                Auth::logout();
                session()->flush();
                session()->regenerate();
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.')
                    ->with('session_expired', true);
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
} 