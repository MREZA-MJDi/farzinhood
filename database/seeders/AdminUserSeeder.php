<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            return;
        }

        User::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => 'Admin',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
