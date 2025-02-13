<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'super_admin',
            'administrador',
            'especialista',
            'asistente',
            'contratista',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $role = Role::create(['name' => 'Super_admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleEspec = Role::create(['name' => 'Especialista']);
        $roleAsist = Role::create(['name' => 'Asistente']);
        $roleContratista = Role::create(['name' => 'Contratista']);

        // Assign permissions to roles
        $role->syncPermissions(Permission::all());
        $roleAdmin->givePermissionTo('administrador');
        $roleEspec->givePermissionTo('especialista');
        $roleAsist->givePermissionTo('asistente');
        $roleContratista->givePermissionTo('contratista');

        // Create user
        $user = User::create([
            'name' => 'admin',
            'supplier_document' => '00000000',
            'email' => 'admin@sena.edu.co',
            'password' => bcrypt('Admin12345*'),
            'functionary' => 'Funcionario'
        ]);


        // Assign role to user
        $user->assignRole($role);
    }

    /**
     * Crear un permiso si no existe.
     */
    protected function createPermission(string $name, string $guard = 'web')
    {
        if (Permission::where('name', $name)->where('guard_name', $guard)->doesntExist()) {
            Permission::create(['name' => $name, 'guard_name' => $guard]);
        }
    }
}
