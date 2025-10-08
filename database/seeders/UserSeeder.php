<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // admin
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'agama' => 'Islam',
            'pekerjaan' => 'Administrator',
            'lingkungan' => 1,
            'alamat' => 'Jl. Admin No. 1',
            'no_hp' => '081234567890',
        ]);

        // Operator
        User::create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // operator
            'nik' => '1234567890123457',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1992-02-02',
            'agama' => 'Islam',
            'pekerjaan' => 'Operator',
            'lingkungan' => 2,
            'alamat' => 'Jl. Operator No. 2',
            'no_hp' => '081234567891',
        ]);
    }
}