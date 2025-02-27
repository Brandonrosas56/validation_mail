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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $role = Role::firstOrCreate(['name' => 'Super_admin', 'guard_name' => 'web']);
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $roleAsist = Role::firstOrCreate(['name' => 'Asistente', 'guard_name' => 'web']);
        $roleContratista = Role::firstOrCreate(['name' => 'Contratista', 'guard_name' => 'web']);

        // Assign permissions to roles
        $role->syncPermissions(Permission::all());
        $roleAdmin->givePermissionTo('administrador');
        $roleAsist->givePermissionTo('asistente');
        $roleContratista->givePermissionTo('contratista');

        // Create user
        $adminCA = User::firstOrCreate([
            'email' => 'CA-Type@sena.edu.co',
        ], [
            'name' => 'CA',
            'supplier_document' => '00000001',
            'functionary' => 'Director de Area',
            'registrar_id' => 0,
        ]);

        // Assign role to users
        $adminCA->assignRole($role);
        $adminCA->load('roles');
    }
}
