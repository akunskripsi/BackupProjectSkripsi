<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'Admin@gmail.com'],
            [
                'name' => 'Admin Toko Surya Elektrik',
                'password' => Hash::make('adminadmin'),
                'status' => 'approved',
                'role_id' => 1,
            ]
        );

        // User Biasa
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('useruser'),
                'status' => 'approved',
                'role_id' => 2,
            ]
        );
    }
}
