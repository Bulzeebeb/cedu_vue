<?php

namespace App\Http\Controllers\PayPark;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PayPark\ParkingSetting;

class ParkingSettingsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rate_minute'     => 'required|integer',
            'rate_fee'        => 'required|numeric',
            'parking_period'  => 'required|string',
            'overtime_fee'    => 'nullable|numeric',
            'overnight_fee'   => 'nullable|numeric',
            'opening_hour'    => 'required',
            'closing_hour'    => 'required',
        ]);

        ParkingSetting::updateOrCreate(
            ['id' => 1], // Always keep one record
            $validated
        );

        // 📝 Insert into audit logs
        DB::table('audit_logs')->insert([
            'user_id'    => Auth::id(),
            'action'     => 'Updated Parking Settings',
            'details'    => 'Updated parking settings: ' . json_encode($validated),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Parking settings updated successfully.');
    }
}
