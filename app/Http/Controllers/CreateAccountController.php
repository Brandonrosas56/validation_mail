<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreateAccount;

class CreateAccountController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'regional' => 'required|string|max:255',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'correo_personal' => 'required|email|unique:create_account,correo_personal',
            'numero_contrato' => 'required|string|max:255',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
        ]);

        CreateAccount::create($request->all());

        return redirect()->back()->with('success', 'Solicitud creada correctamente.');
    }
}
