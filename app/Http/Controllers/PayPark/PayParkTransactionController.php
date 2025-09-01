<?php

namespace App\Http\Controllers\PayPark;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PayPark\PayParkTransaction;
use App\Models\PayPark\PayParkClient;
use App\Models\AuditLog; // ✅ Add this for logging
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayParkTransactionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'client_id' => 'required|exists:pay_park_clients,id',
                'total_payment' => 'required|numeric',
                'transaction_date' => 'required|date',
            ]);

            DB::beginTransaction();

            $client = PayParkClient::findOrFail($request->client_id);
            $client->status = 'Paid';
            $client->time_out = now();
            $client->save();

            $transaction = PayParkTransaction::create([
                'client_id' => $client->id,
                'transaction_date' => Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s'),
                'total_payment' => $request->total_payment,
            ]);

            // ✅ Log the action
            AuditLog::create([
                'user_id'    => Auth::id(),
                'user_type'  => Auth::user() ? get_class(Auth::user()) : 'Guest',
                'action'     => 'Created PayPark Transaction',
                'details'    => json_encode([
                    'transaction_id' => $transaction->id,
                    'client_id'      => $client->id,
                    'total_payment'  => $request->total_payment
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            DB::commit();

            return response()->json($transaction);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction error: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:pay_park_clients,id',
            'name' => 'required|string',
            'plate' => 'required|string',
        ]);

        $client = PayParkClient::find($validated['id']);

        if ($client->name !== $validated['name'] || $client->plate !== $validated['plate']) {
            return response()->json(['error' => 'Client data mismatch'], 400);
        }

        // ✅ Log the scan action
        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_type'  => Auth::user() ? get_class(Auth::user()) : 'Guest',
            'action'     => 'Scanned Client',
            'details'    => json_encode([
                'client_id' => $client->id,
                'name'      => $client->name,
                'plate'     => $client->plate
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client scan successful',
            'client' => $client
        ]);
    }

    public function todayWithClients()
    {
        $today = now()->toDateString();

        $transactions = DB::table('paypark_transactions as t')
            ->join('pay_park_clients as c', 't.client_id', '=', 'c.id')
            ->whereDate('t.transaction_date', $today)
            ->select(
                't.id as transaction_id',
                't.transaction_date',
                't.total_payment',
                'c.id as client_id',
                'c.name',
                'c.plate',
                'c.time_in',
                'c.time_out',
                'c.status',
                'c.qr_code'
            )
            ->orderBy('t.transaction_date')
            ->get();

        $firstClientName = $transactions->first()->name ?? null;
        if ($transactions->count() > 1 && $firstClientName) {
            $firstClientName .= ' et al.';
        }

        $totalPaymentSum = $transactions->sum('total_payment');

        return response()->json([
            'name' => $firstClientName,
            'others_checked' => true,
            'pay_to_park_cost' => $totalPaymentSum,
            'transactions' => $transactions
        ]);
    }

    public function show($id)
    {
        $transaction = PayParkTransaction::findOrFail($id);
        return response()->json($transaction);
    }
}
