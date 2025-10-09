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
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role_id' => 1, // admin
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'agama' => 'Islam',
                'pekerjaan' => 'Administrator',
                'lingkungan' => 1,
                'alamat' => 'Jl. Admin No. 1',
                'no_hp' => '081234567890',
            ]
        );

        // Operator
        User::updateOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password123'),
                'role_id' => 2, // operator
                'nik' => '1234567890123457',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1992-02-02',
                'agama' => 'Islam',
                'pekerjaan' => 'Operator',
                'lingkungan' => 2,
                'alamat' => 'Jl. Operator No. 2',
                'no_hp' => '081234567891',
            ]
        );

        // Test User (Warga) - Update existing user
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $testUser->update([
                'password' => Hash::make('password123'),
            ]);
        } else {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // warga
                'nik' => '1234567890123458',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1995-03-03',
                'agama' => 'Islam',
                'pekerjaan' => 'Warga',
                'lingkungan' => 3,
                'alamat' => 'Jl. Test No. 3',
                'no_hp' => '081234567892',
            ]);
        }

        // Test Registration User (Warga) - Update existing user
        $testRegUser = User::where('email', 'testreg@example.com')->first();
        if ($testRegUser) {
            $testRegUser->update([
                'password' => Hash::make('password123'),
            ]);
        } else {
            User::create([
                'name' => 'Test Registration User',
                'email' => 'testreg@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // warga
                'nik' => '1234567890123459',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1996-04-04',
                'agama' => 'Islam',
                'pekerjaan' => 'Warga',
                'lingkungan' => 4,
                'alamat' => 'Jl. TestReg No. 4',
                'no_hp' => '081234567893',
            ]);
        }
    }
}