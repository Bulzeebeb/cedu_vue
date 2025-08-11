<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogsTableSeeder extends Seeder
{
    public function run()
    {
        $logs = [];

        for ($i = 1; $i <= 10; $i++) {
            $logs[] = [
                'name' => 'User ' . $i,
                'role' => $i % 2 == 0 ? 'Admin' : 'Staff',
                'action' => $i % 2 == 0 ? 'Updated profile information' : 'Generated report',
                'created_at' => Carbon::now()->subDays(10 - $i),
                'updated_at' => Carbon::now()->subDays(10 - $i),
            ];
        }

        DB::table('activity_logs')->insert($logs);
    }
}