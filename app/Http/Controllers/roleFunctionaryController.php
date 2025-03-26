<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Regional;
use Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class roleFunctionaryController extends Controller
{
    //! Muestra la lista de usuarios, roles y regionales
    public function show()
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado
        $roles = Role::all(); // Obtiene todos los roles
        $regionals = Regional::all(); // Obtiene todas las regionales

        // Obtiene los usuarios registrados por el usuario autenticado o todos si es el usuario con ID 1
        $users = User::with('roles', 'regional')->where('registrar_id', $userId)
            ->orWhere(function ($query) use ($userId) {
                if ($userId == 1) {
                    $query->whereNotNull('id');
                }
            })->get();

        return view('forms.form-of-role-and-functionary', compact('users', 'roles', 'regionals'));
    }

    //! Asigna roles y permisos a los usuarios seleccionados
    public function assignRoleFuncionary(Request $request)
    {
        $usersSelect = $request->user_check ?? []; // Usuarios seleccionados

        // Si la acción es bloquear usuarios, llama a la función correspondiente
        if ($request->function === 'lock') {
            return $this->lockUsers($usersSelect);
        }

        try {
            // Validaciones para la selección de rol o tipo de funcionario
            if ($request->select_role === 'select_rol' and $request->Select_functionary === 'Select_functionary') {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado un rol o tipo de funcionario para asignar'])
                    ->withInput();
            } elseif (empty(array_filter($usersSelect))) {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado usuario para asignar'])
                    ->withInput();
            } else {
                // Asignación de rol si fue seleccionado
                if ($request->select_role !== 'select_rol') {
                    $role = Role::findByName($request->select_role);
                    User::whereIn('id', $usersSelect)->each(function ($user) use ($role) {
                        $user->syncRoles($role);
                    });
                }

                // Asignación de tipo de funcionario si fue seleccionado
                if ($request->Select_functionary !== 'Select_functionary') {
                    $functionaryValue = $request->Select_functionary;
                    User::whereIn('id', $usersSelect)->update(['functionary' => $functionaryValue]);
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'No se ha podido registrar'])
                ->withInput();
        }
        return redirect()->back()->with('success', 'Rol asignado correctamente');
    }

    //! Bloquea o desbloquea usuarios seleccionados
    public function lockUsers($usersSelect)
    {
        try {
            if (empty(array_filter($usersSelect))) {
                return redirect()->back()
                    ->withErrors(['error' => 'No ha seleccionado usuario para asignar'])
                    ->withInput();
            } else {
                // Cambia el estado de bloqueo de los usuarios seleccionados
                User::whereIn('id', $usersSelect)->update(['lock' => DB::raw('NOT lock')]);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'No se ha podido bloquear o desbloquear'])
                ->withInput();
        }
        return redirect()->back()->with('success', 'El proceso de bloqueo/desbloqueo se ha completado correctamente.');
    }
}
