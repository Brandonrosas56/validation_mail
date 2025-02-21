<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\LdapService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $ldapService;

    public function __construct(LdapService $ldapService)
    {
        $this->ldapService = $ldapService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        if ($this->ldapService->autenticarUsuario($username, $password)) {
            // Aquí podrías autenticar al usuario en Laravel si ya existe en la base de datos
            return redirect()->route('dashboard')->with('success', 'Autenticación correcta');
        }

        return redirect()->route('login')->withErrors(['username' => 'Credenciales inválidas en LDAP']);
    }
}
