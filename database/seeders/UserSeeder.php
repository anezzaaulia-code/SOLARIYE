<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 1,
            ]
        );

        // Kasir
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'nama' => 'Kasir 1',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'status' => 1,
            ]
        );
    }
}
