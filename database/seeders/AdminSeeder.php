<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Lumina',
            'email' => 'admin@lumina.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Beberapa member dummy untuk testing halaman index anggota
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@lumina.test',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'active',
            'birth_date' => '2003-05-14',
            'phone' => '081234567890',
            'address' => 'Jl. Perintis Kemerdekaan, Makassar',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@lumina.test',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'suspended',
            'birth_date' => '2001-11-02',
            'phone' => '081298765432',
            'address' => 'Jl. Sultan Hasanuddin, BauBau',
            'email_verified_at' => now(),
        ]);
    }
}