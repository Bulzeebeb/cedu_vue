<?php

namespace App\Http\Controllers\PayPark;

use Inertia\Inertia;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayPark\PayParkClient;
use App\Models\AuditLog;

class PayParkClientController extends Controller
{
    public function index()
    {
        try {
            $clients = PayParkClient::orderByDesc('created_at')->get();

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Viewed Clients List',
                'details'    => 'Viewed all PayPark clients',
                'ip_address' => request()->ip(),
            ]);

            return Inertia::render('PayToPark/staff_Dashboard', [
                'clients' => $clients
            ]);
        } catch (\Exception $e) {
            return Inertia::render('PayToPark/staff_Dashboard', [
                'clients' => [],
                'fetchError' => 'Failed to fetch client data: ' . $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plate' => 'required|string|max:255',
            'status' => 'required|string',
            'time_in' => 'required|date',
            'time_out' => 'nullable|date',
        ]);

        $client = PayParkClient::create([
            ...$validated,
            'qr_code' => '',
        ]);

        $qrContent = json_encode([
            'id' => $client->id,
            'name' => $client->name,
            'plate' => $client->plate,
        ]);

        $filename = 'qrcodes/' . Str::uuid() . '.png';
        $result = Builder::create()
            ->data($qrContent)
            ->size(200)
            ->margin(10)
            ->build();

        Storage::disk('public')->put($filename, $result->getString());
        $client->update(['qr_code' => $filename]);

        // 🔹 Audit Log
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Added New Client',
            'details'    => 'Added client: ' . $client->name . ' (' . $client->plate . ')',
            'ip_address' => $request->ip(),
        ]);

        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = PayParkClient::findOrFail($id);

        // 🔹 Audit Log
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Viewed Client',
            'details'    => 'Viewed client: ' . $client->name . ' (' . $client->plate . ')',
            'ip_address' => request()->ip(),
        ]);

        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $client = PayParkClient::findOrFail($id);
        $data = $request->all();

        if (isset($data['status']) && $data['status'] === 'COMPLETED') {
            $validated = $request->validate([
                'time_out' => 'required|date',
                'status' => 'required|in:COMPLETED',
            ]);

            $client->update([
                'time_out' => $data['time_out'],
                'status' => 'COMPLETED',
            ]);

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Completed Parking Session',
                'details'    => 'Marked client as COMPLETED: ' . $client->name . ' (' . $client->plate . ')',
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['message' => 'Client marked as COMPLETED']);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'plate_number' => 'required|string',
        ]);

        $client->update([
            'name' => $data['name'],
            'plate_number' => $data['plate_number'],
        ]);

        // 🔹 Audit Log
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Updated Client Info',
            'details'    => 'Updated client: ' . $client->name . ' (' . $client->plate_number . ')',
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Client updated']);
    }

    public function destroy($id)
    {
        $client = PayParkClient::findOrFail($id);
        $clientName = $client->name . ' (' . $client->plate . ')';
        $client->delete();

        // 🔹 Audit Log
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Deleted Client',
            'details'    => 'Deleted client: ' . $clientName,
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['message' => 'Client deleted.']);
    }
}
