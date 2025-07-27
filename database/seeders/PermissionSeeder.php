<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder; // Sử dụng Model của Spatie

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'admin.panel.access',
            'roles.view', 'roles.create', 'roles.edit',
            'permissions.view', 'permissions.create', 'permissions.edit',
            'users.view', 'users.create', 'users.edit',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
