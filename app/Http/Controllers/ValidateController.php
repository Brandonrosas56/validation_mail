<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValidateAccount;
use App\Models\Regional;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Services\SendValidationStatusService;

class ValidateController extends Controller
{

    public function show()
    {
        $user = Auth()->user();
        $exists = ValidateAccount::where('documento_proveedor', $user->supplier_document)->exists();

        if ($user->hasRole('Contratista')) {
            if ($exists) {
                $accounts = ValidateAccount::where('documento_proveedor', $user->supplier_document)->get();
            } else {
                $accounts = [];
            }
        } else {
            $accounts = ValidateAccount::all();
        }

        $regional = Regional::all('rgn_id', 'rgn_nombre');

        return view('tables.ShowValidateAccount', compact('accounts', 'regional'));
    }

    public function index()
    {
        return view('forms.ActivateAccount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rgn_id' => 'required',
            'exists:regional,rgn_id',
            'documento_proveedor' => 'required|String|',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'correo_personal' => 'required|email|unique:validate_account,correo_personal',
            'correo_institucional' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/|unique:validate_account,correo_institucional',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
            'numero_contrato' => 'required|string|max:255',
            'user_id' => 'required|string',
            'usuario' => 'required|string|max:255|unique:validate_account,usuario',
        ]);

        $documentoProveedor = $request->input('documento_proveedor');
        $numeroContrato = $request->input('numero_contrato');
        $estadoContrato = "En ejecución";
        $usuarioAsignado = $request->input('user_id');
        $correoInstitucional = $request->input('correo_institucional');

        ValidateAccount::create($request->all());

        $User = User::find($request->user_id);
        $contractor = $User->getService()->isContractor();
        if (!$this->validarContratoSecop($documentoProveedor, $numeroContrato, $estadoContrato, $usuarioAsignado)) {
             return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.');
         } else {
        $validacionNemotenia = $this->validarNemotenia($documentoProveedor, $correoInstitucional);
        switch ($validacionNemotenia) {
            case 'No existe el correo':
                $SendValidationStatusService = new SendValidationStatusService($request->all(), SendValidationStatusService::NEMOTECNIA_ERROR, $contractor);
                $SendValidationStatusService->sendTicket();
                break;
            case 'El correo no pertenece a este usuario':
                dd($validacionNemotenia);
                break;
            case 'Activar correo':
                dd($validacionNemotenia);
                break;
        }
        // }



        return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
    }


    private function validarContratoSecop($documentoProveedor, $numeroContrato, $request)
    {
        $apiUrl = "https://www.datos.gov.co/resource/jbjy-vk9h.json?"
            . "\$where=documento_proveedor='$documentoProveedor' AND id_contrato='$numeroContrato' AND estado_contrato='En ejecución'";

        try {
            $response = Http::get($apiUrl);
            $data = $response->json();

            if (isset($data['error']) || isset($data['message']) || !is_array($data) || count($data) === 0) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function validarNemotenia($documentoProveedor, $correoInstitucional)
    {
        $estadoCorreo = '';
        $caseCorreo = '';
        $estadoCorreo = User::where('email', $correoInstitucional)
            ->doesntExist();
        if ($estadoCorreo) {
            $caseCorreo = 'No existe el correo';
            return $caseCorreo;
        } else {
            $estadoCorreo = User::where('email', $correoInstitucional)
                ->where('supplier_document', '!=', $documentoProveedor)
                ->exists();
            if ($estadoCorreo) {
                $caseCorreo = 'El correo no pertenece a este usuario';
                return $caseCorreo;
            } else {
                $estadoCorreo = User::where('email', $correoInstitucional)
                    ->where('supplier_document', $documentoProveedor)
                    ->exists();
                if ($estadoCorreo) {
                    $caseCorreo = 'Activar correo';
                    return $caseCorreo;
                }
            }
        }
    }
}
