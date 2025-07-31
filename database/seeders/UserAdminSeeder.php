<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'account_id' => 1,
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin'
        ]);

        User::create([
            'account_id' => 2,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
    }
}
