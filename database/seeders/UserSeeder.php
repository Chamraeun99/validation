<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // Client user
        User::firstOrCreate(
            ['email' => 'chamraeun@gmail.com'],
            [
                'name'     => 'chamraeun',
                'password' => bcrypt('password'),
                'role'     => 'client',
            ]
        );
    }
}
