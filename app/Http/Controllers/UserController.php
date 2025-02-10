<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Genera las opciones de correo para un usuario o varios usuarios.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarCorreos(Request $request)
    {
        // Si se proporciona un solo ID, generamos los correos de ese usuario
        if ($request->has('id')) {
            $id = $request->input('id');
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Obtener las opciones de correo usando el método del modelo
            $correos = $user->generarCorreos(
                $user->primer_nombre, 
                $user->segundo_nombre, 
                $user->primer_apellido, 
                $user->segundo_apellido
            );

            // Retornar las opciones de correo en formato JSON
            return response()->json($correos);
        }

        // Si no se proporciona un solo ID, generamos los correos para múltiples usuarios
        if ($request->has('ids')) {
            $ids = $request->input('ids');
            
            // Validar que se proporcionen IDs correctos
            if (!is_array($ids)) {
                return response()->json(['error' => 'El parámetro "ids" debe ser un arreglo de IDs válidos.'], 400);
            }

            $usuarios = User::whereIn('id', $ids)->get();

            if ($usuarios->isEmpty()) {
                return response()->json(['error' => 'No se encontraron usuarios con esos IDs'], 404);
            }

            // Generar los correos para cada usuario
            $correosMasivos = $usuarios->map(function($usuario) {
                return $usuario->generarCorreos(
                    $usuario->primer_nombre, 
                    $usuario->segundo_nombre, 
                    $usuario->primer_apellido, 
                    $usuario->segundo_apellido
                );
            });

            // Retornar las opciones de correo en formato JSON
            return response()->json($correosMasivos);
        }

        // Si no se pasa ni un ID ni un arreglo de IDs, generar correos masivos con paginación
        $usuariosPaginados = User::generarCorreosMasivosPaginados();

        // Retornar los resultados paginados en formato JSON
        return response()->json($usuariosPaginados);
    }
}
