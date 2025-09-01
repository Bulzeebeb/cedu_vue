<?php

namespace App\Http\Controllers\PayPark;

use App\Models\PayPark\PayParkClient;
use App\Models\PayPark\PayparkTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class PayParkHistoryController extends Controller
{
    public function index()
    {
        $history = DB::table('pay_park_clients as c')
            ->leftJoin('paypark_transactions as t', 'c.id', '=', 't.client_id')
            ->select(
                'c.id',
                'c.name',
                'c.plate',
                'c.time_in',
                'c.time_out',
                'c.status',
                't.total_payment as total_amount'
            )
            ->get();

        // 🔹 Log this action
        AuditLog::create([
            'user_id'   => Auth::id(),
            'action'    => 'Viewed Parking History',
            'details'   => 'Viewed ' . $history->count() . ' history records',
            'ip_address'=> request()->ip(),
        ]);

        return Inertia::render('PayToPark/parking_History', [
            'entries' => $history,
        ]);
    }

    public function dashboardStats()
    {
        $today = Carbon::today();

        $stats = [
            'totalVehiclesParkedToday' => DB::table('pay_park_clients')
                ->whereDate('time_in', $today)
                ->count(),

            'vehiclesStillIn' => DB::table('pay_park_clients')
                ->whereNull('time_out')
                ->count(),

            'vehiclesOutToday' => DB::table('pay_park_clients')
                ->whereDate('time_out', $today)
                ->count(),

            'totalSalesToday' => DB::table('paypark_transactions')
                ->whereDate('transaction_date', $today)
                ->sum('total_payment'),
        ];

        // 🔹 Log this action
        AuditLog::create([
            'user_id'   => Auth::id(),
            'action'    => 'Viewed Dashboard Stats',
            'details'   => 'Checked parking stats for ' . $today->toDateString(),
            'ip_address'=> request()->ip(),
        ]);

        return response()->json($stats);
    }
}
