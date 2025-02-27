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
            $LdapService = new LdapService();
            if ($LdapService->autenticarUsuario($request->get('email'), $request->get('password'))) {
                // Encuentra o crea un usuario en la BD
                $user = User::where(
                        'email',
						$request->get('email'),
                )->first();
				
				if (empty($user)){
					$user = User::create([ 'email'=>$request->get('email'),
                        'name' => explode('@', $request->get('email'))[0],]
						);
				 }
				 
				$user->load('roles');
                Auth::login($user);
            } else {
                throw new Exception("No se logro autenticar en el Directorio Activo", 1);
            }

            return redirect()->intended('dashboard');
        } catch (Exception $e) {
            if (trim($e->getMessage()) === "Can't contact LDAP server") {
                $user = User::where(
                    'email',
                    $request->get('email'),

                )->first();

                if ($user)  {
                    $user = User::create([
                        'name' => explode('@', $request->get('email'))[0],
                        'email' => $request->get('email')
                    ]);
                }
            
                $user->load('roles');
                Auth::login($user);

                return redirect()->intended('dashboard');
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
