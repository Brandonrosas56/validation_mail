<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
use Illuminate\Http\Request;

class SolicitanteController extends Controller
{
    public function generarIdentificador($id)
    {
        // Obtener los datos del solicitante por su ID
        $solicitante = Solicitante::find($id);
        
        if (!$solicitante) {
            return response()->json(['error' => 'Solicitante no encontrado'], 404);
        }

        // Extracción de los nombres y apellidos
        $primer_nombre = strtolower($solicitante->primer_nombre);
        $segundo_nombre = strtolower($solicitante->segundo_nombre ?? '');
        $primer_apellido = strtolower($solicitante->primer_apellido);
        $segundo_apellido = strtolower($solicitante->segundo_apellido ?? '');

        // Crear las opciones de identificador
        $opcion1 = substr($primer_nombre, 0, 1) . $primer_apellido;  // "jmendieta"
        $opcion2 = substr($primer_nombre, 0, 1) . $primer_apellido . substr($segundo_apellido, 0, 1);  // "jmendietah"
        $opcion3 = substr($primer_nombre, 0, 1) . substr($segundo_nombre, 0, 1) . $primer_apellido;  // "jfmendieta"
        $opcion4 = substr($primer_nombre, 0, 1) . substr($segundo_nombre, 0, 1) . $primer_apellido . substr($segundo_apellido, 0, 1);  // "jfmendietah"

        // Generar el correo con alguna de las opciones
        $dominio = '@sena.edu.co'; 
        $correo1 = $opcion1 . $dominio;
        $correo2 = $opcion2 . $dominio;
        $correo3 = $opcion3 . $dominio;
        $correo4 = $opcion4 . $dominio;


        return response()->json([
            'opcion1' => $correo1,
            'opcion2' => $correo2,
            'opcion3' => $correo3,
            'opcion4' => $correo4,
        ]);
    }
}
