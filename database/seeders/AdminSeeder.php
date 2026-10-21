<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Utama Administrator
        User::updateOrCreate(
            ['email' => 'admin@pybeginner.com'],
            [
                'name' => 'ADMIN',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Siswa Contoh (sesuai aktivitas di mockup)
        User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password123'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'dewi@gmail.com'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password123'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]
        );
    }
}
