<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CreateAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\Regional;


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
        // Validación de los datos del formulario
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
        ]);

        // Luego proceder con los valores
        $documentoProveedor = $request->input('documento_proveedor');
        $numeroContrato = $request->input('numero_contrato');
        $estadoContrato = 'En ejecución';


        // Validar con la API del SECOP
        // if (!$this->validarContratoSecop($documentoProveedor, $numeroContrato, $estadoContrato)) {
        //     return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.');
        // }

        // Guardar los datos si pasa la validación
        CreateAccount::create($request->all());

        return redirect()->back()->with('success', 'Solicitud creada correctamente.');
    }


    /**
     * Método para validar el contrato en el SECOP
     */
    private function validarContratoSecop($documentoProveedor, $numeroContrato, $estadoContrato)
    {

        $apiUrl = "https://www.datos.gov.co/resource/jbjy-vk9h.json?"
            . "\$where=documento_proveedor='$documentoProveedor' AND id_contrato>='$numeroContrato'";

        try {
            $response = Http::get($apiUrl);
            $data = $response->json();

            return !empty($data);  // Si la API devuelve datos, el contrato es válido
        } catch (\Exception $e) {
            return false; // En caso de error, se asume que la validación falló
        }
    }
}
