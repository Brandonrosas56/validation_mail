<?php

namespace App\Services;

use Symfony\Component\Ldap\Ldap;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Ldap\Exception\LdapException;

class LdapService
{
    private $ldap;

    /**
     * Constructor de la clase.
     * Inicializa la conexión con el servidor LDAP utilizando las variables de entorno.
     */
    public function __construct()
    {
        $this->ldap = Ldap::create('ext_ldap', [
            'host' => env('LDAP_HOST'),
            'port' => env('LDAP_PORT'),
        ]);
    }

    /**
     * Autentica a un usuario en el servidor LDAP.
     *
     * @param string $usuario - Nombre de usuario para autenticación
     * @param string $password - Contraseña del usuario
     * @return bool - Retorna true si la autenticación es exitosa, false en caso contrario
     */
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