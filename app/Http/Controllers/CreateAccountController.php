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
    /**
     * Muestra las cuentas creadas por el usuario autenticado o sus registrados.
     *
     * Este método obtiene las cuentas de usuario relacionadas con el usuario autenticado
     * y las cuentas de los usuarios registrados bajo su ID. Si el usuario autenticado
     * es el administrador (ID = 1), se obtienen todas las cuentas.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Obtiene el ID del usuario autenticado
        $userId = Auth::id();

        // Obtiene los IDs de los usuarios registrados bajo el ID del usuario autenticado
        $registrarIds = User::where('registrar_id', $userId)->pluck('id');

        // Obtiene las regiones para mostrar en el formulario
        $regional = Regional::all(['rgn_id', 'rgn_nombre']);

        // Crea la consulta para obtener las cuentas del usuario autenticado o sus registrados
        $accountsQuery = CreateAccount::with('regional')
            ->where('user_id', $userId)
            ->orWhereIn('user_id', $registrarIds);

        // Si el usuario es el administrador (ID = 1), muestra todas las cuentas
        if ($userId == 1) {
            $accountsQuery = CreateAccount::with('regional');
        }

        // Paginación de las cuentas
        $accounts = $accountsQuery->paginate(20);

        // Retorna la vista con las cuentas y las regiones
        return view('tables.ShowCreateAccount', compact('accounts', 'regional'));
    }

    /**
     * Muestra el formulario para crear una nueva cuenta.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('forms.CreateAccount');
    }

    /**
     * Almacena una nueva solicitud de creación de cuenta.
     *
     * Este método valida los datos de la solicitud, verifica si ya existe una solicitud
     * con el mismo documento en estado 'rechazado' o 'exitoso', y si no existe, 
     * crea una nueva cuenta. Si el rol asignado es 'Contratista', valida el contrato
     * con el servicio de Secop.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Si el rol asignado es 'Funcionario', asigna una fecha de terminación predeterminada
            if ($request->rol_asignado === 'Funcionario') {
                $date_termination = '1000-01-01';
                $request->merge(['fecha_terminacion_contrato' => $date_termination]);
            }
    
            // Obtiene el ID del usuario autenticado y lo agrega a la solicitud
            $userId = Auth::id();
            $request->merge(['user_id' => $userId]);
    
            // Verifica si ya existe una solicitud con el mismo documento en estado 'rechazado' o 'exitoso'
            $existingRequest = CreateAccount::where('documento_proveedor', $request->documento_proveedor)
                ->whereIn('estado', ['rechazado', 'exitoso'])
                ->exists();
    
            // Si existe, redirige con un mensaje de error
            if ($existingRequest) {
                return redirect()->back()->withErrors(['error' => 'Ya existe una solicitud con este documento en estado Rechazado o Exitoso. No puedes crear otra.'])->withInput();
            }
    
            // Valida los datos recibidos en la solicitud
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
    
            // Excluye el campo 'operation' y crea la cuenta
            $requestData = $request->except('operation');
            $createAccount = CreateAccount::create($requestData);
    
            // Si el rol asignado es 'Contratista', valida el contrato con el servicio Secop
            if ($request->rol_asignado === 'Contratista') {
                $documentoProveedor = $request->input('documento_proveedor');
                $numeroContrato = $request->input('numero_contrato');
    
                // Si el contrato no es válido, envía un ticket de validación y redirige con un mensaje
                if (!SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                    $sendValidationStatusService = new SendValidationStatusService($createAccount, SendValidationStatusService::TEMPLATE_PENDING_CONTRACTOR_CREACION);
                    $sendValidationStatusService->sendTicket();
                    return redirect()->back()->withErrors(['error' => 'Nos encontramos validando su solicitud'])->withInput();
                } else {
                    // Si el contrato es válido, envía la plantilla de éxito
                    $sendValidationStatusService = new SendValidationStatusService($createAccount, SendValidationStatusService::TEMPLATE_SUCCESS_CONTRACTOR_CREACION);
                    $sendValidationStatusService->sendTicket();
                    return redirect()->back()->with('success', 'Solicitud de contratista creada y validada correctamente.');
                }
            } elseif ($request->rol_asignado === 'Funcionario') {
                // Si el rol es 'Funcionario', enviamos la plantilla de pendiente de funcionario
                $sendValidationStatusService = new SendValidationStatusService($createAccount, SendValidationStatusService::TEMPLATE_PENDING_FUNCTIONARY_CREACION);
                $sendValidationStatusService->sendTicket();
                return redirect()->back()->with('success', 'Solicitud de funcionario creada correctamente.');
            }
        } catch (\Throwable $th) {
            // Si ocurre un error, redirige con un mensaje de error
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }
}