<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create initial permissions (Add more later based on requirements)
        $permissions = [
            'view dashboard',
            'manage inventory',
            'manage users',
            'view reports',
            'manage sales',
            'manage purchases'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions

        // 1. Superadmin Role
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        // Superadmin gets all permissions
        $superadminRole->givePermissionTo(Permission::all());

        // 2. User/Staff Role (Basic access)
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view dashboard',
            'manage sales',
        ]);

        // Assign superadmin role to existing superadmins based on old logic, 
        // or just the very first user created to not lose access.
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('superadmin');
            $this->command->info('Granted superadmin role to: ' . $firstUser->email);
        }
    }
}
