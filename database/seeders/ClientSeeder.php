<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('client_accounts')->insert([
            'firstName' => 'Juan',
            'middleName' => 'Santos',
            'lastName' => 'Dela Cruz',
            'extension' => '',
            'address' => 'Tagum City',
            'contactNum' => '09123456789',
            'gender' => 'Male',
            'age' => 25,
        ]);
    }
}
