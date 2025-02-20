<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;

class ChangeStatusController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'state' => 'required',
                'account_id' => 'required',
                'type' => 'required'
            ]);

            $Account = $request->get('type') == CreateAccount::CREATE_ACCOUNT ? CreateAccount::find($request->get('account_id')) : ValidateAccount::find($request->get('account_id'));

            if ($Account) {
                if ($Account->getService()->changeStatus($request->get('state'))) {
                    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
                } else {
                    return redirect()->back()->with('error', 'No se pudo realizar el cambio de estado');
                }
            } else {
                return redirect()->back()->with('error', 'No se encontro la cuenta');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }
}
