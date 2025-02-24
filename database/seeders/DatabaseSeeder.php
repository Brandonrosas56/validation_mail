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
            'asistente',
            'contratista',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $role = Role::create(['name' => 'Super_admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleAsist = Role::create(['name' => 'Asistente']);
        $roleContratista = Role::create(['name' => 'Contratista']);

        // Assign permissions to roles
        $role->syncPermissions(Permission::all());
        $roleAdmin->givePermissionTo('administrador');
        $roleAsist->givePermissionTo('asistente');
        $roleContratista->givePermissionTo('contratista');

        // Create user
        $user = User::create([
            'name' => 'admin',
            'supplier_document' => '00000000',
            'email' => 'admin@sena.edu.co',
            'password' => bcrypt('Admin12345*'),
            'functionary' => 'Director de Area',
            'registrar_id' => 0,
        ]);


        // Assign role to user
        $user->assignRole(roles: $role);
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
