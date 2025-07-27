<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'super_admin',
                'password' => bcrypt('password'), // Use bcrypt() helper
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Assign the 'super-admin' role to the user
        $superAdminUser->assignRole('super-admin');
    }
}
