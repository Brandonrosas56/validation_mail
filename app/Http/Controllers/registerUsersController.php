<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class registerUsersController extends Controller
{
    //! Muestra la lista de usuarios y sus roles
    public function index()
    {
        $roles = Role::select('name')->get(); // Obtiene los roles disponibles
        $regional = Regional::all('rgn_id', 'rgn_nombre'); // Obtiene todas las regionales
        $users = User::with('roles', 'regional')->get(); // Obtiene los usuarios con sus roles y regionales
        return view('auth.user-authorization', compact('users', 'roles', 'regional'));
    }

    //! Crea un nuevo usuario
    public function store(Request $request)
    {
        // Validación de los datos del usuario
        $validator = Validator::make($request->all(), [
            'supplier_document' => ['string', 'unique:users'],
            'email' => ['required', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@sena\.edu\.co$/'],
            'password' => ['min:8', 'confirmed', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/'],
            'Select_functionary' => ['not_in:default'],
            'rgn_id' => ['required', 'exists:regional,rgn_id'],
        ]);

        if ($validator->fails()) {
            session()->flash('error_messages', $validator->errors()->all());
            return redirect()->back()->withInput();
        }

        $userId = Auth::id(); // Obtiene el ID del usuario autenticado

        // Crea el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'supplier_document' => $request->supplier_document,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encripta la contraseña
            'rgn_id' => $request->rgn_id,
            'registrar_id' => $userId,
            'position' => $request->Select_functionary,
        ]);

        $user->assignRole($request->rol); // Asigna el rol al usuario
        return redirect()->route('show_user_authorization')->with('success', 'El nuevo usuario ha sido creado con éxito.');
    }

    //! Actualiza la información de un usuario existente
    public function restore(Request $request, $id)
    {
        // Reglas de validación para actualizar usuario
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $id,
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@sena.edu.co')) {
                        $fail('El correo debe ser @sena.edu.co');
                    }
                }
            ],
            'rol' => ['required', 'exists:roles,name'],
        ];

        // Si la contraseña está presente, se agregan reglas adicionales
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/'];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(($validator))->withInput();
        }

        //! Busca el usuario por ID
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->syncRoles([$request->rol]); // Sincroniza el rol del usuario
        $user->save(); // Guarda los cambios
        return redirect()->route('registerUsers')->with('success', '¡Datos actualizados correctamente!');
    }

    //! Bloquea o desbloquea un usuario
    public function blockUser(Request $request)
    {
        // Validación del usuario a bloquear
        $validator = Validator::make($request->all(), [
            'bloked_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['errors' => 'No ha seleccionado ningún usuario para bloquear'])->withInput();
        }

        $user = User::find($request->bloked_id);
        // Alternar estado de bloqueo
        $user->user_blocked = !$user->user_blocked;
        $user->save();

        return redirect()->route('auth.user-authorization')->with('success', '¡Datos actualizados correctamente!');
    }
}
