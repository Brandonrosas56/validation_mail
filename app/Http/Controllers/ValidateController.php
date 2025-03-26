<?php

namespace App\Http\Controllers;

use App\Services\SecopService;
use Illuminate\Http\Request;
use App\Models\ValidateAccount;
use App\Models\Regional;
use App\Models\User;
use App\Services\SendValidationStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ValidateController extends Controller
{
    //! Muestra la lista de cuentas a validar
    public function show()
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado
        $registrarIds = User::where('registrar_id', $userId)->pluck('id'); // Obtiene los IDs de usuarios registrados por el actual usuario

        // Obtiene las cuentas de validación asociadas al usuario actual o a los usuarios que ha registrado
        $accounts = ValidateAccount::with('regional')
            ->where('user_id', $userId)
            ->orWhereIn('user_id', $registrarIds)
            ->orWhere(function ($query) use ($userId) {
                if ($userId == 1) {
                    $query->whereNotNull('id');
                }
            })
            ->paginate(20);

        $regional = Regional::all('rgn_id', 'rgn_nombre'); // Obtiene todas las regionales

        return view('tables.ShowValidateAccount', compact('accounts', 'regional'));
    }

    //! Muestra el formulario de activación de cuenta
    public function index()
    {
        return view('forms.ActivateAccount');
    }

    //! Almacena una nueva solicitud de activación de cuenta
    public function store(Request $request)
    {
        try {
            // Si el usuario es un funcionario, se establece una fecha de terminación fija
            if ($request->rol_asignado === 'Funcionario') {
                $date_termination = '1000-01-01';
                $request->merge(['fecha_terminacion_contrato' => $date_termination]);
            }

            // Normaliza el correo y usuario a minúsculas
            $request->merge([
                'correo_institucional' => strtolower($request->correo_institucional),
                'usuario' => strtolower($request->usuario),
            ]);

            // Asigna el ID del usuario autenticado
            $request->merge(['user_id' => Auth::id()]);

            // Validación de los datos ingresados
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

            // Crea la cuenta de validación con los datos ingresados
            $validateAccount = ValidateAccount::create($request->all());

            // Manejo de la lógica de validación según el rol
            if ($request->rol_asignado === 'Contratista') {
                $documentoProveedor = $request->input('documento_proveedor');
                $numeroContrato = $request->input('numero_contrato');

                // Verifica si el contrato es válido en SECOP
                if (!SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                    // Si el contrato no es válido, envía la plantilla de pendiente
                    $sendValidationStatusService = new SendValidationStatusService($validateAccount, SendValidationStatusService::TEMPLATE_PENDING_CONTRACTOR_ACTIVACION);
                    $sendValidationStatusService->sendTicket();
                    return redirect()->back()->with('error', 'Nos encontramos validando su solicitud');
                } else {
                    // Si el contrato es válido, envía la plantilla de éxito
                    $sendValidationStatusService = new SendValidationStatusService($validateAccount, SendValidationStatusService::TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION);
                    $sendValidationStatusService->sendTicket();
                    return redirect()->back()->with('success', 'Solicitud de contratista validada correctamente.');
                }
            } elseif ($request->rol_asignado === 'Funcionario') {
                // Si el rol es 'Funcionario', envía la plantilla de pendiente de funcionario
                $sendValidationStatusService = new SendValidationStatusService($validateAccount, SendValidationStatusService::TEMPLATE_PENDING_FUNCTIONARY_ACTIVACION);
                $sendValidationStatusService->sendTicket();
                return redirect()->back()->with('success', 'Solicitud de funcionario creada correctamente.');
            }
        } catch (\Throwable $th) {
            // Si ocurre un error, redirige con un mensaje de error
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }
}