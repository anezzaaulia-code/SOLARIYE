<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Akun Admin (Hanya jika belum ada)
        User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Cek email ini dulu
            [
                'nama' => 'Super Admin',
                'password' => Hash::make('password'), // Ganti password sesukamu
                'role' => 'admin',
                
            ]
        );

        // Bikin Akun Kasir (Opsional, buat ngetes aja)
        User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'nama' => 'Kasir Utama',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                
            ]
        );
    }
}