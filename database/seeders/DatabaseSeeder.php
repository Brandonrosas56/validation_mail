<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Permission::create(['name' => 'admin_users']);
        Permission::create(['name' => 'admin_files']);
        Permission::create(['name' => 'view_files']);
        Permission::create(['name' => 'admin_audit']);

        // Create roles
        $role = Role::create(['name' => 'Admin']);

        // Assign permissions to roles
        $role->givePermissionTo(Permission::all());

        // Create user
        $user = User::create([
            'name' => 'admin3',
            'email' => 'admin3@sena.edu.co',
            'password' => bcrypt('Admin12345*')
        ]);

        // Assign role to user
        $user->assignRole($role);
    }



      /**
     * Create a permission if it does not already exist.
     *
     * @param string $name
     * @param string $guardName
     * @return void
     */
    protected function createPermission(string $name, string $guardName)
    {
        if (Permission::where('name', $name)->where('guard_name', $guardName)->doesntExist()) {
            Permission::create([
                'name' => $name,
                'guard_name' => $guardName,
            ]);
        }
    }
}