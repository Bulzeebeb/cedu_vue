<?php

namespace App\Http\Controllers\PayPark;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PayParkAdminReportController extends Controller
{
    public function index()
    {
        $reports = DB::table('pay_park_clients as c')
            ->leftJoin('paypark_transactions as t', 'c.id', '=', 't.client_id')
            ->select(
                'c.id',
                'c.name',
                'c.plate',
                'c.qr_code',
                'c.time_in',
                'c.time_out',
                'c.status',
                't.total_payment as total_amount'
            )
            ->orderByDesc('c.time_in')
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'name'         => $item->name ?? '',
                    'plate'        => $item->plate ?? '',
                    // ✅ Fix: Full URL to image in storage
                    'qr'           => $item->qr_code 
                                        ? asset('storage/qrcodes/' . $item->qr_code) 
                                        : '',
                    'time_in'      => $item->time_in,
                    'time_out'     => $item->time_out,
                    'status'       => $item->status ?? '',
                    'total_amount' => (float) ($item->total_amount ?? 0),
                ];
            });

        return Inertia::render('PayToPark/admin_parking_reports', [
            'entries' => $reports
        ]);
    }
    
}
