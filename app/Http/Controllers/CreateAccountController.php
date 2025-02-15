<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CreateAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\Regional;
use App\Services\SendValidationStatusService;


class CreateAccountController extends Controller
{
    public function show()
    {
        $user = Auth()->user();
        $exists = CreateAccount::where('documento_proveedor', $user->supplier_document)->exists();
        if ($user->hasRole('Contratista')) {
            if ($exists) {
                $accounts = CreateAccount::where('documento_proveedor', $user->supplier_document)->get();
            }else{
                $accounts = [];
            }
        } else {
            $accounts = CreateAccount::all();
        }

        $regional = Regional::all('rgn_id', 'rgn_nombre');

        return view('tables.ShowCreateAccount', compact('accounts', 'regional'));
    }


    public function index()
    {
        return view('forms.CreateAccount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rgn_id' => 'required',
            'exists:regional,rgn_id',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'documento_proveedor' => 'nullable|string|max:255',
            'correo_personal' => 'required|email|unique:create_account,correo_personal',
            'numero_contrato' => 'required|string|max:255',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
            'rol_asignado' => 'required|string',
        ]);

        $documentoProveedor = $request->input('documento_proveedor');
        $numeroContrato = $request->input('numero_contrato');
        $estadoContrato = "En ejeución";
        $usuarioAsignado = $request->input("user_id");

        CreateAccount::create($request->all());
        
        if (!$this->validarContratoSecop($documentoProveedor, $numeroContrato, $estadoContrato, $usuarioAsignado)) {
            return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.');
        }
        
        return redirect()->back()->with('success', 'Solicitud creada correctamente.');
    }


    private function validarContratoSecop($documentoProveedor, $numeroContrato)
    {

        $apiUrl = "https://www.datos.gov.co/resource/jbjy-vk9h.json?"
            . "\$where=documento_proveedor='$documentoProveedor' AND id_contrato='$numeroContrato' AND estado_contrato='En ejecución'";

        try {
            $response = Http::get($apiUrl);
            $data = $response->json();
    
            if (isset($data['error']) || isset($data['message'])) {
                return false; 
            }
    
            return is_array($data) && count($data) > 0;
    
        } catch (\Exception $e) {
            return false; 
        }
    }
}
