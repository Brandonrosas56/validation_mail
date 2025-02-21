<?php

namespace App\Service;

use Symfony\Component\Ldap\Ldap;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Ldap\Exception\LdapException;

class LdapService
{
    private $ldap;

    public function __construct()
    {
        $this->ldap = Ldap::create('ext_ldap', [
            'host' => env('LDAP_HOST'),
            'port' => env('LDAP_PORT'),
        ]);
    }

    public function autenticarUsuario(string $usuario, string $password): bool
    {
        try {
            $this->ldap->bind($usuario, $password);
            return true;
        } catch (LdapException $e) {
            Log::error("Error en autenticación LDAP: " . $e->getMessage());
            return false;
        }
    }
}
