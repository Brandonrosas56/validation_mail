<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class registerUsersController extends Controller
{
    //! show users and roles
    public function index(Request $request)
    {
        $roles = role::select('name')->get();
        $regional = Regional::all('rgn_id', 'rgn_nombre');
        $users = user::with('roles', 'regional')->get(); 
        return view('auth.register', compact('users', 'roles', 'regional'));
    }

    //!Create user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_document' => ['required', 'string'],
            'email' => ['required','unique:users'],
            'password' => ['required', 'min:8', 'confirmed', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/',],
            'rgn_id' =>['required', 'exists:regional,rgn_id'],
            'rol' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(($validator))->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'supplier_document' => $request->supplier_document,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rgn_id' => $request->rgn_id,
        ]);
        $user->assignRole($request->rol);
        return redirect()->route('registerUsers')->with('success', '¡Datos guardados correctamente!');
    }



    //!Update user
    public function restore(Request $request, $id)
    {
        // Configure basic validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'unique:users,email,' . $id,
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@sena.edu.co')) {
                        $fail('El correo debe ser @sena.edu.co');
                    }
                }
            ],
            'rol' => ['required', 'exists:roles,name'],
        ];
        // If the password field is present and not empty, add validation rules for the password
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/',];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(($validator))->withInput();
        }

        //!Collect the id of the one you want to modify
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->syncRoles([$request->rol]);
        $user->save();
        return redirect()->route('registerUsers')->with('success', '¡Datos actualizados correctamente!');
    }

    //!blocked user
    public function blockUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bloked_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors('No ha seleccionado ningún usuario para blockear')->withInput();
        }

        $user = User::find($request->bloked_id);
        if (!$user->user_blocked) {
            $user->user_blocked = true;
        } else {
            $user->user_blocked = false;
        }

        $user->save();
        return redirect()->route('registerUsers')->with('success', '¡Datos actualizados correctamente!');
    }
}
