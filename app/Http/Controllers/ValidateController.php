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
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;


class ValidateController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $registrarIds = User::where('registrar_id', $userId)->pluck('id');

        $accounts = ValidateAccount::with('regional')->where('user_id', $userId)
            ->orWhereIn('user_id', $registrarIds)
            ->orWhere(function ($query) use ($userId) {
                if ($userId == 1) {
                    $query->whereNotNull('id');
                }
            })->paginate(20);

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
            if ($request->rol_asignado === 'Funcionario') {
                $date_termination = '1000-01-01';
                $request->merge(['fecha_terminacion_contrato' => $date_termination]);
            }
            $request->merge([
                'correo_institucional' => strtolower($request->correo_institucional),
                'usuario' => strtolower($request->usuario),
            ]);
            $request->merge(['user_id' => Auth::id()]);
            $request->validate([
                'rgn_id' => 'required|exists:regional,rgn_id',
                'documento_proveedor' => [
                    'required',
                    'string',
                    Rule::unique('validate_account', 'documento_proveedor')->where(function ($query) {
                        return $query->whereIn('estado', ['rechazado', 'exitoso']);
                    }),
                ],
                'tipo_documento' => 'required|string',
                'primer_nombre' => 'required|string|max:255',
                'segundo_nombre' => 'nullable|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'correo_personal' => 'required|email',
                'correo_institucional' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/',
                'fecha_inicio_contrato' => 'required|date',
                'fecha_terminacion_contrato' => 'nullable|date',
                'numero_contrato' => 'required|string|max:255',
                'rol_asignado' => 'required|string',
                'usuario' => 'required|string|max:255',
            ], [
                'documento_proveedor.unique' => 'Ya existe una solicitud con este documento en estado Rechazado o Exitoso. No puedes crear otra.',
            ]);

          
            $ValidateAccount = ValidateAccount::create($request->all());
            $documentoProveedor = $request->input('documento_proveedor');
            $numeroContrato = $request->input('numero_contrato');

            $isContractor = $ValidateAccount->getService()->isContractor();
            if ($isContractor && !SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                $SendValidationStatusService = new SendValidationStatusService($ValidateAccount, SendValidationStatusService::TEMPLATE_PENDING);
                $SendValidationStatusService->sendTicket();
                return redirect()->back()->with('error', 'Nos encontramos validando su solicitud');
            }

            return redirect()->back()->with('success', 'Solicitud de activación creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error-modal', $th->getMessage())->withInput();
        }
    }
}
