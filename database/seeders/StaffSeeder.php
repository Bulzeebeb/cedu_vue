<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('staff_accounts')->insert([
            'firstName' => 'Maria',
            'middleName' => 'Lopez',
            'lastName' => 'Reyes',
            'extension' => '',
            'address' => 'Davao City',
            'contactNum' => '09998887777',
            'gender' => 'Female',
            'age' => 28,
        ]);
    }
}
