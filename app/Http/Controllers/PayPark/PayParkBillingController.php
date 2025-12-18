<?php

namespace App\Http\Controllers\PayPark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayPark\PayParkClient;
use App\Models\PayPark\PayParkBilling;
use Carbon\Carbon;

class PayParkBillingController extends Controller
{
    public function compute($clientId)
{
    $client = PayParkClient::findOrFail($clientId);
    $rate = PayParkBilling::first();

    $timeIn = Carbon::parse($client->time_in);
    $timeOut = Carbon::now();

    $totalMinutes = ceil($timeOut->diffInMinutes($timeIn));
    $totalHours = ceil($totalMinutes / 60);

    $standardMinutes = ($rate->rate_hour * 60) + $rate->rate_minute;
    $totalAmount = $rate->rate_fee;

    if ($totalMinutes > $standardMinutes) {
        $excessMinutes = $totalMinutes - $standardMinutes;
        $excessHours = ceil($excessMinutes / 60);
        $totalAmount += $excessHours * $rate->overtime_fee;
    }

    return response()->json([
        'client_id'    => $client->id,
        'name'         => $client->name,   // ✅ added
        'plate'        => $client->plate,  // ✅ added
        'total_hours'  => $totalHours,
        'total_amount' => $totalAmount,
        'time_in'      => $client->time_in,
        'time_out'     => $timeOut->toDateTimeString()
    ]);
}

}
