<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@example.com',
            'nomor_handphone' => '081234567890',
            'alamat' => 'Jl. Admin Utama No. 1',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN->value,
            'gender' => Gender::LAKI_LAKI->value,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'email_verified_at' => now(),
        ]);

       
    }
}