<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;

class ChangeStatusController extends Controller
{
    /**
     * Almacena el cambio de estado de una cuenta.
     *
     * Este método valida la solicitud entrante, encuentra la cuenta relevante 
     * según el ID de cuenta proporcionado, y luego intenta cambiar el estado 
     * de la cuenta. Dependiendo del resultado, redirige de vuelta con un mensaje 
     * de éxito o error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validación de los datos recibidos en la solicitud
            $request->validate([
                'state' => 'required',        // El estado debe ser proporcionado
                'account_id' => 'required',   // El ID de la cuenta debe ser proporcionado
                'type' => 'required'          // El tipo de cuenta debe ser especificado
            ]);

            // Determina qué modelo usar según el tipo de cuenta recibido
            $Account = $request->get('type') == CreateAccount::CREATE_ACCOUNT 
                ? CreateAccount::find($request->get('account_id'))  // Si es una cuenta de creación, busca en CreateAccount
                : ValidateAccount::find($request->get('account_id')); // Si no, busca en ValidateAccount

            // Verifica si la cuenta fue encontrada
            if ($Account) {
                // Intenta cambiar el estado de la cuenta utilizando el servicio asociado
                if ($Account->getService()->changeStatus($request->get('state'))) {
                    // Si el cambio de estado es exitoso, redirige con un mensaje de éxito
                    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
                } else {
                    // Si no se pudo cambiar el estado, redirige con un mensaje de error
                    return redirect()->back()->with('error', 'No se pudo realizar el cambio de estado');
                }
            } else {
                // Si no se encontró la cuenta, redirige con un mensaje de error
                return redirect()->back()->with('error', 'No se encontró la cuenta');
            }
        } catch (\Throwable $th) {
            // Si ocurre un error en el proceso, redirige con el mensaje del error
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
