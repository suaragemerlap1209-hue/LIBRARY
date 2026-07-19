<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lumina.test'],
            [
                'name' => 'Admin Lumina',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@lumina.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'active',
                'birth_date' => '2003-05-14',
                'phone' => '081234567890',
                'address' => 'Jl. Perintis Kemerdekaan, Makassar',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@lumina.test'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'suspended',
                'birth_date' => '2001-11-02',
                'phone' => '081298765432',
                'address' => 'Jl. Sultan Hasanuddin, BauBau',
                'email_verified_at' => now(),
            ]
        );
    }
}