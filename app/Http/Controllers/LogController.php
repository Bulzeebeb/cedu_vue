<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function getLogs()
    {
        $logs = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}