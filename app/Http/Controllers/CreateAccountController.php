<?php

namespace App\Http\Controllers;

use App\Services\SecopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreateAccount;
use App\Models\Regional;
use App\Models\User;
use App\Services\SendValidationStatusService;
use Illuminate\Validation\Rule;

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

        $accounts = $accountsQuery->paginate(20);

        return view('tables.ShowCreateAccount', compact('accounts', 'regional'));
    }

    public function index()
    {
        return view('forms.CreateAccount');
    }

    public function store(Request $request)
{
    try {
        if ($request->rol_asignado === 'Funcionario') {
            $date_termination = '1000-01-01';
            $request->merge(['fecha_terminacion_contrato' => $date_termination]);
        }

        $userId = Auth::id();
        $request->merge(['user_id' => $userId]);

        $existingRequest = CreateAccount::where('documento_proveedor', $request->documento_proveedor)
            ->whereIn('estado', ['rechazado', 'exitoso'])
            ->exists();

        if ($existingRequest) {
            return redirect()->back()->withErrors(['error' => 'Ya existe una solicitud con este documento en estado Rechazado o Exitoso. No puedes crear otra.'])->withInput();
        }

        $request->validate([
            'rgn_id' => 'required|exists:regional,rgn_id',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'documento_proveedor' => 'required|string|max:255', 
            'tipo_documento' => 'required|string|max:50',
            'correo_personal' => 'required|email',
            'rol_asignado' => 'required|string|in:Funcionario,Contratista',
            'user_id' => 'required|exists:users,id',
            'numero_contrato' => 'required|string|max:255',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_terminacion_contrato' => 'nullable|date',
        ]);

        $requestData = $request->except('operation');
        $createAccount = CreateAccount::create($requestData);

        if ($request->rol_asignado === 'Contratista') {
            $documentoProveedor = $request->input('documento_proveedor');
            $numeroContrato = $request->input('numero_contrato');

            if (!SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                $sendValidationStatusService = new SendValidationStatusService($createAccount, SendValidationStatusService::SECOP_ERROR);
                $sendValidationStatusService->sendTicket();
                return redirect()->back()->withErrors(['error' => 'Nos encontramos validando su solicitud'])->withInput();
            }
        }

        return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
    } catch (\Throwable $th) {
        return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
    }
}
}