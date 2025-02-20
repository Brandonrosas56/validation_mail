<?php

namespace App\Http\Controllers;

use App\Services\SecopService;
use Illuminate\Http\Request;
use App\Models\ValidateAccount;
use App\Models\Regional;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Services\SendValidationStatusService;
use Illuminate\Support\Facades\Auth;

class ValidateController extends Controller
{

    public function show()
    {
        $userId = Auth::id();
        $registrarIds = User::where('registrar_id', $userId)->pluck('id');

        $accounts = ValidateAccount::with('regional')->where('user_id', $userId)->orWhereIn('user_id', $registrarIds)
            ->orWhere(function ($query) use ($userId) {
                if ($userId == 1) {
                    $query->whereNotNull('id');
                }
            })->get();

        $regional = Regional::all('rgn_id', 'rgn_nombre');

        return view('tables.ShowValidateAccount', compact('accounts', 'regional'));
    }

    public function index()
    {
        return view('forms.ActivateAccount');
    }
    public function store(Request $request)
    {
        try {
            $request->merge([
                'correo_institucional' => strtolower($request->correo_institucional),
                'usuario' => strtolower($request->usuario),
            ]);

            $request->validate([
                'rgn_id' => 'required|exists:regional,rgn_id',
                'documento_proveedor' => 'required|string',
                'tipo_documento' => 'required|string',
                'primer_nombre' => 'required|string|max:255',
                'segundo_nombre' => 'nullable|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'correo_personal' => 'required|email|unique:validate_account,correo_personal',
                'correo_institucional' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/|unique:validate_account,correo_institucional',
                'fecha_inicio_contrato' => 'required|date',
                'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
                'numero_contrato' => 'required|string|max:255',
                'rol_asignado' => 'required|string',
                'usuario' => 'required|string|max:255|unique:validate_account,usuario',
                'user_id' => 'required',
            ]);

            $documentoProveedor = $request->input('documento_proveedor');
            $numeroContrato = $request->input('numero_contrato');
            $correoInstitucional = $request->input('correo_institucional');

            $ValidateAccount = ValidateAccount::create($request->all());

            $isContractor = $ValidateAccount->isContractor();

            if ($isContractor && !SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                $SendValidationStatusService = new SendValidationStatusService($ValidateAccount, SendValidationStatusService::SECOP_ERROR);
                $SendValidationStatusService->sendTicket();
                return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.');
            } else {
                $validacionNemotenia = $this->validarNemotenia($documentoProveedor, $correoInstitucional);
                switch ($validacionNemotenia) {
                    case 'No existe el correo':
                        $SendValidationStatusService = new SendValidationStatusService($ValidateAccount, SendValidationStatusService::NEMOTECNIA_ERROR, $isContractor);
                        $SendValidationStatusService->sendTicket();
                        break;
                    case 'El correo no pertenece a este usuario':
                        dd($validacionNemotenia);
                        break;
                    case 'Activar correo':
                        dd($validacionNemotenia);
                        break;
                }
            }

            return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error-modal', $th->getMessage())->withInput();
        }
    }
}
