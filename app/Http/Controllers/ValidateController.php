<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValidateAccount;
use App\Models\Regional;

class ValidateController extends Controller
{

    public function show()
    {
        $user = Auth()->user();
        $exists = ValidateAccount::where('documento_proveedor', $user->supplier_document)->exists();

        if ($user->hasRole('Contratista')) {
            if($exists){
                $accounts = ValidateAccount::where('documento_proveedor', $user->supplier_document)->get();
            }else{
                $accounts = [];
            }   
        }else{
            $accounts = ValidateAccount::all();
        }

        $regional = Regional::all('rgn_id', 'rgn_nombre');

        return view('tables.ShowValidateAccount', compact('accounts','regional'));
    }

    public function index()
    {
        return view('forms.ActivateAccount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rgn_id' =>'required', 'exists:regional,rgn_id',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'documento_proveedor' => 'required|String|',
            'correo_personal' => 'required|email|unique:validate_account,correo_personal',
            'correo_institucional' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/|unique:validate_account,correo_institucional',
            'numero_contrato' => 'required|string|max:255',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
            'usuario' => 'required|string|max:255|unique:validate_account,usuario',
        ]);

        ValidateAccount::create($request->all());

        return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
    }
}
