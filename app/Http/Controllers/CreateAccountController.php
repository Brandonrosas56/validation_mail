<?php

namespace App\Http\Controllers;

use App\Services\SecopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreateAccount;
use App\Models\Regional;
use App\Models\User;
use App\Services\SendValidationStatusService;

class CreateAccountController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $registrarIds = User::where('registrar_id', $userId)->pluck('id');

        $regional = Regional::all(['rgn_id', 'rgn_nombre']);

        $accountsQuery = CreateAccount::with('regional')
            ->where('user_id', $userId)
            ->orWhereIn('user_id', $registrarIds);

        if ($userId == 1) {
            $accountsQuery = CreateAccount::with('regional');
        }

        $accounts = $accountsQuery->get();

        return view('tables.ShowCreateAccount', compact('accounts', 'regional'));
    }

    public function index()
    {
        return view('forms.CreateAccount');
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $request->merge(['user_id' => $userId]);

            // Validación base
            $rules = [
                'rgn_id' => 'required|exists:regional,rgn_id',
                'primer_nombre' => 'required|string|max:255',
                'segundo_nombre' => 'nullable|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'documento_proveedor' => 'requiere|string|max:255',
                'tipo_documento' => 'required|string|max:50',
                'correo_personal' => 'required|email|unique:create_account,correo_personal',
                'rol_asignado' => 'required|string|in:Funcionario,Contratista',
                'user_id' => 'required|exists:users,id',
                'numero_contrato' => 'required|string|max:255',
                'fecha_inicio_contrato' => 'required|date',
            ];

            if ($request->rol_asignado === 'Contratista') {
                $rules += [
                    'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
                ];
            }

            $request->validate($rules);

            if ($request->rol_asignado === 'Contratista') {
                $documentoProveedor = $request->input('documento_proveedor');
                $numeroContrato = $request->input('numero_contrato');

                if (!SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                    return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.')->withInput();
                }
            }

            // Crear cuenta
            $createAccount = CreateAccount::create($request->all());

            // Enviar validación de estado
            $sendValidationStatusService = new SendValidationStatusService($createAccount, SendValidationStatusService::SECOP_ERROR);
            $sendValidationStatusService->sendTicket();

            return redirect()->back()->with('success', 'Solicitud creada correctamente.');
        } catch (\Throwable $th) {
            \Log::error('Error al crear cuenta: ' . $th->getMessage());
            return redirect()->back()->with('error-modal', 'Ocurrió un error. Inténtelo de nuevo.' . $th->getMessage())->withInput();
        }
    }
}
