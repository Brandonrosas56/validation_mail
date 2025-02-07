<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Genera las opciones de correo para un usuario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarCorreo(int $id)
    {
        // Obtener el usuario por su ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Obtener las opciones de correo usando el método del modelo
        $correos = $user->generarOpcionesCorreo();

        // Retornar las opciones en formato JSON
        return response()->json($correos);
    }
}

