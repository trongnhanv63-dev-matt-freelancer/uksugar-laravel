<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // The list of foundational permissions for the application
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Role & Permission Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Other permissions can be added here
        ];

        // Loop through the permissions and create them if they do not exist
        foreach ($permissions as $permissionSlug) {
            Permission::firstOrCreate(
                ['slug' => $permissionSlug],
                ['description' => "Permission to {$permissionSlug}"]
            );
        }
    }
}
