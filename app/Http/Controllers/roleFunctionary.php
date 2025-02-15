<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Regional;
use Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class roleFunctionary extends Controller
{
    public function show()
    {
        $roles = Role::all();
        $regionals = Regional::all();
        $users = user::with('roles', 'regional')->get();

        return view('forms.form-of-role-and-functionary', compact('users', 'roles', 'regionals'));
    }

    public function assignRoleFuncionary(Request $request)
    {
        $usersSelect = $request->user_check ?? [];
        if ($request->function === 'lock') {
            return $this->lockUsers($usersSelect);
        }

        try {
            if ($request->select_role === 'select_rol' and $request->Select_functionary === 'Select_functionary') {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado un rol o tipo de funcionario para asignar'])
                    ->withInput();
            } elseif (empty(array_filter($usersSelect))) {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado usuario para asignar'])
                    ->withInput();
            } else {
                if ($request->select_role !== 'select_rol') {
                    $role = Role::findByName($request->select_role);
                    User::whereIn('id', $usersSelect)->each(function ($user) use ($role) {
                        $user->syncRoles($role);
                    });
                }

                if ($request->Select_functionary !== 'Select_functionary') {
                    $functionaryValue = $request->Select_functionary;
                    User::whereIn('id', $usersSelect)->update(['functionary' => $functionaryValue]);
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'No se ha podido registrar'])
                ->withInput();
        }
        return redirect()->back()->with('success', 'Se ha asignado correctamente');
    }

    public function lockUsers($usersSelect)
    {
        try {
            if (empty(array_filter($usersSelect))) {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado usuario para asignar'])
                    ->withInput();
            } else {
                User::whereIn('id', $usersSelect)->update(['lock' => DB::raw('NOT lock')]);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'No se ha podido bloquear o desbloquear'])
                ->withInput();
        }
        return redirect()->back()->with('success', 'Se ha realizado el proceso de bloqueo');
    }
}
