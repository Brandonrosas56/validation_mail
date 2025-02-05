<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class rolesController extends Controller
{
    //!show the roles
    public function showRolView()
    {
        $names = $this->nameRoles();
        return view('rol.rol', compact('names'));
    }

    //! Assigns the names of the permissions you have
    public function nameRoles()
    {
        //Get the database roles and permissions
        $roles = role::all();
        $permissions = Permission::all();
        $names = [];

        if (!$roles->isEmpty() and !$permissions->isEmpty()) {
            foreach ($roles as $role) {
                $permissionNames = [];

                foreach ($permissions as $permission) {
                    if ($role->hasPermissionTo($permission)) {
                        // Assigns a readable name based on the permission name
                        switch ($permission->name) {
                            case 'admin_users':
                                $permissionNames[] = "Administrador de usuarios";
                                break;
                            case 'admin_files':
                                $permissionNames[] = "Administrador de archivos";
                                break;
                            case 'view_files':
                                $permissionNames[] = "Vista de archivos";
                                break;
                            case 'admin_audit':
                                $permissionNames[] = "Administrador de auditoria";
                                break;
                        }
                    }
                }

                // Adds the role names obtained for this role to the $names array and permissions
                $names[] = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $permissionNames
                ];
            }
        }

        // return los names and permissions
        return $names;
    }


    //! register roles and assign permissions
    public function store(Request $request)
    {
        //Validate names y permissions
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        //create role
        $role = role::create(['name' => $request->name, 'guard_name' => 'web']);

        //Assign permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('show-rol-view')->with('success', '¡Datos guardados correctamente!');
    }

    // //!Update roles
    public function restore(Request $request, $id){
        //validate name and permissions
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            //validate name of role
            $existingRole = role::where('name', $request->input('name'))
                ->where('id', '!=', $id)
                ->first();

            if ($existingRole) {
                return redirect()->back()->withErrors(['name' => 'El nombre ya está en uso por otro rol.'])->withInput();
            }
        }


        $roles = role::findOrFail($id);

         //Assign permissions
         if ($request->has('permissions')) {
            $roles->syncPermissions($request->permissions);
        }

        $roles->save();

        return redirect()->route('show-rol-view')->with('success', '¡Datos actualizados correctamente!');
    }
}
