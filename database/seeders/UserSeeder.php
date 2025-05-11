<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailAdmin = env('ADMIN_EMAIL') ?? '';
        $passAdmin = env('ADMIN_PASSWORD') ?? '';
        if (!empty($emailAdmin) && !empty($passAdmin)) {
            //init user admin
            User::updateOrCreate(
                [
                    'email' => $emailAdmin,
                ],
                [
                    'name' => 'Admin',
                    'password' => bcrypt($passAdmin),
                ]
            );
        }
    }
}
