<?php

namespace App\Http\Controllers;

use App\Services\SecopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CreateAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\Regional;
use App\Models\User;
use App\Services\SendValidationStatusService;


class CreateAccountController extends Controller
{
    public function show()
    {

        $userId = Auth::id();
        $registrarIds = User::where('registrar_id', $userId)->pluck('id');
        $regional = Regional::all('rgn_id', 'rgn_nombre');
        $accounts = CreateAccount::with('regional')->where('user_id', $userId)->orWhereIn('user_id', $registrarIds)
            ->orWhere(function ($query) use ($userId) {
                if ($userId == 1) {
                    $query->whereNotNull('id');
                }
            })->get();

        return view('tables.ShowCreateAccount', compact('accounts', 'regional'));
    }


    public function index()
    {
        return view('forms.CreateAccount');
    }

    public function store(Request $request)
    {
        try {
            $request->merge(['user_id' => Auth::id()]);
            $request->validate([
                'rgn_id' => 'required',
                'exists:regional,rgn_id',
                'primer_nombre' => 'required|string|max:255',
                'segundo_nombre' => 'nullable|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'documento_proveedor' => 'nullable|string|max:255',
                'tipo_documento' => 'required|String|',
                'correo_personal' => 'required|email|unique:create_account,correo_personal',
                'numero_contrato' => 'required|string|max:255',
                'fecha_inicio_contrato' => 'required|date',
                'fecha_terminacion_contrato' => 'required|date|after_or_equal:fecha_inicio_contrato',
                'rol_asignado' => 'required|string',
                'user_id' => 'required',
            ]);

            $documentoProveedor = $request->input('documento_proveedor');
            $numeroContrato = $request->input('numero_contrato');
            $CreateAccount = CreateAccount::create($request->all());

            if (!SecopService::isValidSecopContract($documentoProveedor, $numeroContrato)) {
                $SendValidationStatusService = new SendValidationStatusService($CreateAccount, SendValidationStatusService::SECOP_ERROR);
                $SendValidationStatusService->sendTicket();
                return redirect()->back()->with('error', 'El contrato no está vigente según el SECOP.');
            }

            return redirect()->back()->with('success', 'Solicitud creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error-modal', $th->getMessage())->withInput();
        }
    }



}
