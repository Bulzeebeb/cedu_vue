<?php

namespace App\Http\Controllers\PayPark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayPark\PayParkClient;
use App\Models\PayPark\PayParkTransaction;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Inertia\Inertia;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffDashboardController extends Controller
{
    /**
     * Display the staff dashboard with all clients
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        try {
            $clients = PayParkClient::orderByDesc('created_at')->get();

            // Get the authenticated user (check both web and admin guards)
            $user = Auth::user() ?? Auth::guard('admin')->user();
            $admin = null;

            if ($user) {
                // If staff, load staff account
                if ($user->role === 'staff' && $user->staffAccount) {
                    $admin = [
                        'id' => $user->id,
                        'first_name' => $user->staffAccount->firstName,
                        'last_name' => $user->staffAccount->lastName,
                        'email' => $user->email,
                        'role' => $user->role,
                    ];
                } else {
                    // Fallback for admin or other users
                    $admin = [
                        'id' => $user->id,
                        'first_name' => $user->first_name ?? 'Staff',
                        'last_name' => $user->last_name ?? '',
                        'email' => $user->email,
                        'role' => $user->role,
                    ];
                }
            }

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Viewed Staff Dashboard',
                'details'    => 'Accessed staff dashboard with ' . count($clients) . ' clients',
                'ip_address' => request()->ip(),
            ]);

            return Inertia::render('PayToPark/staff_Dashboard', [
                'clients' => $clients,
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            Log::error('Staff Dashboard Error: ' . $e->getMessage());

            $user = Auth::user() ?? Auth::guard('admin')->user();
            $admin = null;

            if ($user) {
                // If staff, load staff account
                if ($user->role === 'staff' && $user->staffAccount) {
                    $admin = [
                        'id' => $user->id,
                        'first_name' => $user->staffAccount->firstName,
                        'last_name' => $user->staffAccount->lastName,
                        'email' => $user->email,
                        'role' => $user->role,
                    ];
                } else {
                    // Fallback for admin or other users
                    $admin = [
                        'id' => $user->id,
                        'first_name' => $user->first_name ?? 'Staff',
                        'last_name' => $user->last_name ?? '',
                        'email' => $user->email,
                        'role' => $user->role,
                    ];
                }
            }

            return Inertia::render('PayToPark/staff_Dashboard', [
                'clients' => [],
                'admin' => $admin,
                'fetchError' => 'Failed to fetch client data: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get dashboard statistics (total vehicles, sales, etc.)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            $yesterdayEnd = Carbon::yesterday()->endOfDay();

            $stats = [
                'totalVehiclesParkedToday' => DB::table('pay_park_clients')
                    ->whereDate('time_in', $today)
                    ->count(),

                'vehiclesStillIn' => DB::table('pay_park_clients')
                    ->whereNull('time_out')
                    ->count(),

                'vehiclesOutToday' => DB::table('pay_park_clients')
                    ->whereDate('time_out', $today)
                    ->whereNotNull('time_out')
                    ->count(),

                'totalSalesToday' => DB::table('paypark_transactions')
                    ->whereDate('transaction_date', $today)
                    ->sum('total_payment') ?? 0,

                'averageStayTime' => $this->calculateAverageStayTime(),
            ];

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Fetched Dashboard Stats',
                'details'    => json_encode($stats),
                'ip_address' => request()->ip(),
            ]);

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Dashboard Stats Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calculate average parking duration
     *
     * @return float
     */
    private function calculateAverageStayTime()
    {
        $today = Carbon::today();

        $avgMinutes = DB::table('pay_park_clients')
            ->whereDate('time_in', $today)
            ->whereNotNull('time_out')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out)) as avg_minutes')
            ->value('avg_minutes');

        if (!$avgMinutes) {
            return 0;
        }

        return round($avgMinutes / 60, 1); // Convert to hours
    }

    /**
     * Create a new parking client
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'plate' => 'required|string|max:255',
                'status' => 'nullable|string',
                'time_in' => 'nullable|date',
            ]);

            $client = PayParkClient::create([
                'name' => $validated['name'],
                'plate' => $validated['plate'],
                'status' => $validated['status'] ?? 'ACTIVE',
                'time_in' => $validated['time_in'] ?? now(),
                'time_out' => null,
                'qr_code' => '',
            ]);

            // Generate QR code
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
                'action'     => 'Created New Client',
                'details'    => 'Added client: ' . $client->name . ' (' . $client->plate . ')',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'client' => $client
            ], 201);
        } catch (\Exception $e) {
            Log::error('Create Client Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update client information
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateClient(Request $request, $id)
    {
        try {
            $client = PayParkClient::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'plate' => 'required|string|max:255',
                'status' => 'nullable|string',
            ]);

            $client->update([
                'name' => $validated['name'],
                'plate' => $validated['plate'],
                'status' => $validated['status'] ?? $client->status,
            ]);

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Updated Client Information',
                'details'    => 'Updated client: ' . $client->name . ' (' . $client->plate . ')',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            Log::error('Update Client Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get client information
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClient($id)
    {
        try {
            $client = PayParkClient::findOrFail($id);

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Viewed Client Details',
                'details'    => 'Viewed client: ' . $client->name . ' (' . $client->plate . ')',
                'ip_address' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'client' => $client
            ]);
        } catch (\Exception $e) {
            Log::error('Get Client Error: ' . $e->getMessage());
            return response()->json(['error' => 'Client not found'], 404);
        }
    }

    /**
     * Scan QR code and retrieve client information
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanQRCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:pay_park_clients,id',
                'name' => 'required|string',
                'plate' => 'required|string',
            ]);

            $client = PayParkClient::find($validated['id']);

            if ($client->name !== $validated['name'] || $client->plate !== $validated['plate']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client data mismatch'
                ], 400);
            }

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Scanned QR Code',
                'details'    => json_encode([
                    'client_id' => $client->id,
                    'name'      => $client->name,
                    'plate'     => $client->plate
                ]),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code scanned successfully',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            Log::error('QR Scan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process client checkout (exit parking)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkoutClient(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $client = PayParkClient::findOrFail($id);

            if ($client->time_out !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client has already checked out'
                ], 400);
            }

            // Update client status
            $client->update([
                'time_out' => now(),
                'status' => 'COMPLETED',
            ]);

            // Calculate parking duration and fees
            $checkInTime = new Carbon($client->time_in);
            $checkOutTime = new Carbon($client->time_out);
            $durationMinutes = $checkInTime->diffInMinutes($checkOutTime);
            $durationHours = ceil($durationMinutes / 60); // Round up to nearest hour

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Client Checked Out',
                'details'    => json_encode([
                    'client_id' => $client->id,
                    'name'      => $client->name,
                    'plate'     => $client->plate,
                    'duration_hours' => $durationHours,
                    'check_in'  => $client->time_in,
                    'check_out' => $client->time_out,
                ]),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client checkout completed successfully',
                'client' => $client,
                'durationHours' => $durationHours,
                'durationMinutes' => $durationMinutes,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create transaction and process payment
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:pay_park_clients,id',
                'total_payment' => 'required|numeric|min:0',
                'payment_method' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $client = PayParkClient::findOrFail($validated['client_id']);

            if ($client->status !== 'COMPLETED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Client must be checked out before creating transaction'
                ], 400);
            }

            $transaction = PayParkTransaction::create([
                'client_id' => $client->id,
                'transaction_date' => now(),
                'total_payment' => $validated['total_payment'],
                'payment_method' => $validated['payment_method'] ?? 'Cash',
            ]);

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Created Transaction',
                'details'    => json_encode([
                    'transaction_id' => $transaction->id,
                    'client_id'      => $client->id,
                    'amount'         => $validated['total_payment'],
                    'payment_method' => $validated['payment_method'] ?? 'Cash',
                ]),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'transaction' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filtered clients based on search or status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilteredClients(Request $request)
    {
        try {
            $query = PayParkClient::query();

            // Filter by search query
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('plate', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('startDate') && $request->startDate) {
                $query->whereDate('time_in', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->whereDate('time_in', '<=', $request->endDate);
            }

            // Filter for active clients only (not checked out)
            if ($request->get('activeOnly', false)) {
                $query->whereNull('time_out');
            }

            // Sort options
            $sortBy = $request->get('sortBy', 'time_in');
            $sortOrder = $request->get('sortOrder', 'desc');

            $query->orderBy($sortBy, $sortOrder);

            $clients = $query->get();

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Filtered Clients',
                'details'    => json_encode([
                    'search' => $request->search ?? null,
                    'status' => $request->status ?? null,
                    'results' => count($clients),
                ]),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'clients' => $clients,
                'count' => count($clients),
            ]);
        } catch (\Exception $e) {
            Log::error('Filter Clients Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parking history
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParkingHistory(Request $request)
    {
        try {
            $query = PayParkClient::whereNotNull('time_out')
                ->with('transactions');

            // Filter by date range
            if ($request->has('startDate') && $request->startDate) {
                $query->whereDate('time_out', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->whereDate('time_out', '<=', $request->endDate);
            }

            $history = $query->orderByDesc('time_out')
                ->paginate($request->get('per_page', 20));

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Viewed Parking History',
                'details'    => json_encode([
                    'records' => count($history),
                ]),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'history' => $history,
            ]);
        } catch (\Exception $e) {
            Log::error('Parking History Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard summary data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardSummary()
    {
        try {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            $summary = [
                'todayStats' => [
                    'totalVehicles' => DB::table('pay_park_clients')
                        ->whereDate('time_in', $today)
                        ->count(),
                    'vehiclesStillIn' => DB::table('pay_park_clients')
                        ->whereNull('time_out')
                        ->count(),
                    'vehiclesCheckedOut' => DB::table('pay_park_clients')
                        ->whereDate('time_out', $today)
                        ->whereNotNull('time_out')
                        ->count(),
                    'totalSales' => DB::table('paypark_transactions')
                        ->whereDate('transaction_date', $today)
                        ->sum('total_payment') ?? 0,
                    'averageStayTime' => $this->calculateAverageStayTime(),
                ],
                'monthStats' => [
                    'totalVehicles' => DB::table('pay_park_clients')
                        ->whereDate('time_in', '>=', $thisMonth)
                        ->count(),
                    'totalSales' => DB::table('paypark_transactions')
                        ->whereDate('transaction_date', '>=', $thisMonth)
                        ->sum('total_payment') ?? 0,
                    'totalTransactions' => DB::table('paypark_transactions')
                        ->whereDate('transaction_date', '>=', $thisMonth)
                        ->count(),
                ],
                'peakHours' => $this->getPeakParkingHours(),
                'recentActivity' => $this->getRecentActivity(5),
            ];

            // 🔹 Audit Log
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Fetched Dashboard Summary',
                'details'    => 'Retrieved dashboard summary data',
                'ip_address' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get peak parking hours
     *
     * @return array
     */
    private function getPeakParkingHours()
    {
        $today = Carbon::today();

        $peakHours = DB::table('pay_park_clients')
            ->selectRaw('HOUR(time_in) as hour, COUNT(*) as count')
            ->whereDate('time_in', $today)
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        return $peakHours->map(function ($hour) {
            return [
                'hour' => $hour->hour ?? 0,
                'count' => $hour->count ?? 0,
            ];
        })->toArray();
    }

    /**
     * Get recent activity
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentActivity($limit = 5)
    {
        return PayParkClient::whereNotNull('time_out')
            ->orderByDesc('time_out')
            ->limit($limit)
            ->get();
    }
}
