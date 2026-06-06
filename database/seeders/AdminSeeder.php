<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@silelang.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true
        ]);

        User::create([
            'name'      => 'Petugas',
            'email'     => 'petugas@silelang.com',
            'password'  => Hash::make('petugas123'),
            'role'      => 'petugas',
            'is_active' => true
        ]);

        echo "Akun default sudah dibuat:\n";
        echo "------------------------------\n";
        echo "name     : Administrator\n";
        echo "email    : admin@silelang.com\n";
        echo "password : admin123\n";
        echo "------------------------------\n";
        echo "name     : Petugas\n";
        echo "email    : petugas@silelang.com\n";
        echo "password : petugas123\n";
    }
}