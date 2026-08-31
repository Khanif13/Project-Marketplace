<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'              => 'Admin Lapak.id',
            'email'             => 'admin@lapak.id',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'seller_status'     => 'none',
            'email_verified_at' => now(),
        ]);

        // Contoh seller terverifikasi
        User::create([
            'name'              => 'Budi Santoso',
            'email'             => 'budi@example.com',
            'password'          => Hash::make('password'),
            'role'              => 'seller',
            'seller_status'     => 'verified',
            'store_name'        => 'Toko Budi Elektronik',
            'store_address'     => 'Jl. Merdeka No. 12, dekat kampus Unsulbar',
            'store_wa'          => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Contoh buyer biasa
        User::create([
            'name'              => 'Andi Kurniawan',
            'email'             => 'andi@example.com',
            'password'          => Hash::make('password'),
            'role'              => 'buyer',
            'seller_status'     => 'none',
            'email_verified_at' => now(),
        ]);
    }
}
