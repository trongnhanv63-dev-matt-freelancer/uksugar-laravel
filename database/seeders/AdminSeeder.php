<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder creates 10 new admin users each time it is run.
     * It uses the UserFactory to generate unique data for each admin.
     *
     * @return void
     */
    public function run(): void
    {
        // Use the factory to create 10 users.
        // The `create()` method persists them to the database and returns a collection of the created users.
        $admins = User::factory()->count(10)->create();

        // Iterate over each newly created user to assign the 'admin' role.
        $admins->each(function ($admin) {
            // Assign the 'admin' role to the user.
            // Ensure you have a 'admin' role created in your roles table (e.g., using Spatie's permissions package).
            $admin->assignRole('admin');
        });
    }
}
