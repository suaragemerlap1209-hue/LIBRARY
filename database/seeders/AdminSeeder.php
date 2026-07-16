<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

        $this->createMember('Budi Santoso', 'budi@lumina.test', '2003-05-14', 'active', 'Jl. Perintis Kemerdekaan, Makassar');
        $this->createMember('Siti Aminah', 'siti@lumina.test', '2001-11-02', 'suspended', 'Jl. Sultan Hasanuddin, BauBau');
    }

    private function createMember(string $name, string $email, string $birthDate, string $status, string $address): void
    {
        $age = Carbon::parse($birthDate)->age;
        $maxLoans = $age <= 16 ? 3 : 6;

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => $status,
            'birth_date' => $birthDate,
            'address' => $address,
            'max_loans' => $maxLoans,
            'expired_at' => now()->addYears(3),
            'email_verified_at' => now(),
        ]);
    }
}