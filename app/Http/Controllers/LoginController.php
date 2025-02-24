<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LdapService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $LdapService = new LdapService();
            if ($LdapService->autenticarUsuario($request->get('email'), $request->get('password'))) {
                // Encuentra o crea un usuario en la BD
                $user = User::updateOrCreate(
                    [
                        'email' => $request->get('email'),
                        'name' => explode('@', $request->get('email'))[0],
                        'password' => '******',
                        'supplier_document' => 999999,
                        'registrar_id' => 01
                    ],
                );

                Auth::login($user);
            } else {
                throw new Exception("No se logro autenticar en el Directorio Activo", 1);
            }

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
            // return back()->withErrors(['password' => 'Credenciales incorrectas.']);
        }
    }
}
