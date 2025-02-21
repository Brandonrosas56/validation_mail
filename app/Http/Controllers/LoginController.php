<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Service\LdapService;
use Illuminate\Http\Request;
use Symfony\Component\Ldap\Ldap;
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
            $LdapService = new LdapService($request->get('email'), $request->get('password'));
            if ($LdapService->isValid()) {
                // Encuentra o crea un usuario en la BD
                $user = User::updateOrCreate(
                    ['username' => $request->get('email')],
                );
                dd($user);

                #Auth::login($user);
            }else{
                throw new Exception("No se logro autenticar en el Directorio Activo", 1);
            }

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'Credenciales incorrectas.']);
        }
    }
}
