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
        // Crear permisos solo si no existen
        $this->createPermission('admin_users');
        $this->createPermission('admin_files');
        $this->createPermission('view_files');
        $this->createPermission('admin_audit');

        // Crear roles solo si no existen
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Asignar permisos al rol
        $role->givePermissionTo(Permission::all());

        // Crear usuario si no existe
        $user = User::firstOrCreate(
            ['email' => 'admin3@sena.edu.co'],
            [
                'name' => 'admin3',
                'password' => bcrypt('Admin12345*')
            ]
        );

        // Asignar rol al usuario
        if (!$user->hasRole('Admin')) {
            $user->assignRole($role);
        }
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
