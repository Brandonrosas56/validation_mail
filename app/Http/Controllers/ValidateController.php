<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValidateAccount;

class ValidateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'regional' => 'required|string|max:255',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'correo_personal' => 'required|email|unique:activations,correo_personal',
            'correo_institucional' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/|unique:activations,correo_institucional',
            'numero_contrato' => 'required|string|max:255',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
            'usuario' => 'required|string|max:255|unique:activations,usuario',
        ]);

        ValidateAccount::create($request->all());

        return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
    }
}
