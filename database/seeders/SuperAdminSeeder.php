<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Enums\UserStatus;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the Super Admin role
        $superAdminRole = Role::where('name', 'super-admin')->first();

        // Ensure the role exists before creating the user
        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Create the Super Admin user if they do not exist
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // The unique attribute to check for
            [
                'username' => 'superadmin',
                'password' => 'password', // The password will be hashed automatically by the User model's cast
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        // Assign the Super Admin role to the user
        $superAdminUser->roles()->sync([$superAdminRole->id]);
    }
}
