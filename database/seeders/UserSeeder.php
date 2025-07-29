<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get last inserted IDs
        $clientId = DB::table('client_accounts')->orderBy('id', 'desc')->first()->id;
        $staffId = DB::table('staff_accounts')->orderBy('id', 'desc')->first()->id;

        DB::table('users')->insert([
            [
                'account_id' => $clientId,
                'username'   => 'juan123',
                'email'      => 'juan@example.com',
                'password'   => Hash::make('1234'),
                'role'       => 'client',
            ],
            [
                'account_no' => $staffId,
                'username'   => 'maria28',
                'email'      => 'maria@example.com',
                'password'   => Hash::make('1234'),
                'role'       => 'staff',
            ]
        ]);
    }
}
