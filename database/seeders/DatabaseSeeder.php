<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // The order of seeders is important
        $this->call([
            // PermissionSeeder::class,
            // RoleSeeder::class,
            // SuperAdminSeeder::class,
            AdminSeeder::class,
            // Other seeders can be added here
        ]);
    }
}
