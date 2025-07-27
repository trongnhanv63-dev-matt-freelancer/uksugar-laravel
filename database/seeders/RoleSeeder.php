<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin role
        $superAdminRole = Role::create(['name' => 'super-admin']);

        // Create a regular admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Assign some permissions to the admin role
        $adminRole->givePermissionTo([
            'admin.panel.access',
            'users.view',
            'roles.view',
        ]);

        // The logic to assign all permissions to super-admin is removed,
        // as their power comes from the Gate::before() check.
    }
}
