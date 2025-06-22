<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the Super Admin role if it does not exist
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Has all permissions'
            ]
        );

        // Get all permission IDs
        // $allPermissionIds = Permission::pluck('id');

        // Assign all permissions to the Super Admin role
        // $superAdminRole->permissions()->sync($allPermissionIds);
    }
}
