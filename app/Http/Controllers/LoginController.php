<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LdapService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $ldapService = new LdapService();
            
            // Intentar autenticar con LDAP
            if ($ldapService->autenticarUsuario($request->get('email'), $request->get('password'))) {
                // Buscar usuario en la base de datos
                $user = User::where('email', $request->get('email'))->first();

                if (!$user) {
                    return back()->withErrors(['error' => 'No tienes permisos para acceder']);
                }

                // Si el usuario existe pero no tiene roles asignados, bloquear el acceso
                if ($user->roles->isEmpty()) {
                    return back()->withErrors(['error' => 'No tienes permisos para acceder']);
                }

                // Si pasa todas las validaciones, iniciar sesión
                Auth::login($user);
                return redirect()->intended('dashboard');
            } else {
                throw new Exception("No se logró autenticar en el Directorio Activo");
            }
        } catch (Exception $e) {
            // Manejo de error si LDAP no responde
            if (trim($e->getMessage()) === "Can't contact LDAP server") {
                $user = User::where('email', $request->get('email'))->first();

                if (!$user) {
                    return back()->withErrors(['error' => 'No tienes permisos para acceder']);
                }

                if ($user->roles->isEmpty()) {
                    return back()->withErrors(['error' => 'No tienes permisos para acceder']);
                }

                if (!Hash::check($request->get('password'), $user->password)) {
                    return back()->withErrors(['error' => 'Credenciales inválidas']);
                }

                Auth::login($user);
                return redirect()->intended('dashboard');
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

