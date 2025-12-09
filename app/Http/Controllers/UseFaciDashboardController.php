<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UseFaciDashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated admin
        $admin = Auth::guard('admin')->user();

        // Get categories
        $hostelCategory = Category::where('name', 'Hostel')->first();
        $rentalCategory = Category::where('name', 'Rental')->first();
        $commercialCategory = Category::where('name', 'Commercial')->first();

        // Calculate stats
        $stats = [
            'hostel_rooms' => $hostelCategory ? $hostelCategory->facilities()->where('is_active', true)->count() : 0,
            'hostel_active' => $hostelCategory ? Booking::whereHas('facility', function($q) use ($hostelCategory) {
                $q->where('category_id', $hostelCategory->id);
            })->where('status', 'approved')->count() : 0,
            'facilities' => Facility::where('is_active', true)->count(),
            'facilities_active' => Booking::where('status', 'approved')->count(),
            'rental_spaces' => $rentalCategory ? $rentalCategory->facilities()->where('is_active', true)->count() : 0,
            'rental_active' => $rentalCategory ? Booking::whereHas('facility', function($q) use ($rentalCategory) {
                $q->where('category_id', $rentalCategory->id);
            })->where('status', 'approved')->count() : 0,
            'commercial_spaces' => $commercialCategory ? $commercialCategory->facilities()->where('is_active', true)->count() : 0,
            'commercial_active' => $commercialCategory ? Booking::whereHas('facility', function($q) use ($commercialCategory) {
                $q->where('category_id', $commercialCategory->id);
            })->where('status', 'approved')->count() : 0,
        ];

        // Calculate additional stats
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Calculate revenue (assuming facilities have pricing)
        $revenueStats = $this->calculateRevenueStats();

        // Calculate occupancy rates
        $occupancyStats = $this->calculateOccupancyRates($stats);

        // Get recent bookings with more details
        $recentBookings = Booking::with(['facility.category', 'userClient'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'category' => $booking->facility->category->name ?? 'Unknown',
                    'name' => $booking->facility->name,
                    'date' => $booking->created_at->format('Y-m-d'),
                    'status' => $booking->status,
                    'user_name' => $booking->userClient->name ?? 'N/A',
                    'user_email' => $booking->userClient->email ?? 'N/A',
                    'booking_date' => $booking->check_in ? $booking->check_in->format('Y-m-d') : $booking->created_at->format('Y-m-d'),
                    'duration' => $booking->duration ?? 1,
                    'total_amount' => $booking->total_amount ?? 0,
                ];
            });

        // Get alerts/notifications
        $alerts = $this->getDashboardAlerts($pendingBookings);

        // Generate chart data for the last 12 months
        $chartData = $this->generateChartData();

        return Inertia::render('UseFaci_ADMIN/admin_FaciDashboard', [
            'admin' => $admin,
            'stats' => array_merge($stats, [
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'approved_bookings' => $approvedBookings,
                'cancelled_bookings' => $cancelledBookings,
                'occupancy_rates' => $occupancyStats,
                'revenue' => $revenueStats,
            ]),
            'recentBookings' => $recentBookings,
            'chartData' => $chartData,
            'alerts' => $alerts,
        ]);
    }

    private function generateChartData()
    {
        $now = Carbon::now();
        $labels = [];
        $hostelData = [];
        $facilitiesData = [];
        $rentalData = [];
        $commercialData = [];

        // Get categories
        $hostelCategory = Category::where('name', 'Hostel')->first();
        $rentalCategory = Category::where('name', 'Rental')->first();
        $commercialCategory = Category::where('name', 'Commercial')->first();

        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthName = $date->format('M Y');
            $startOfMonth = $date->startOfMonth();
            $endOfMonth = $date->endOfMonth();

            $labels[] = $monthName;

            // Hostel bookings
            $hostelCount = $hostelCategory ? Booking::whereHas('facility', function($q) use ($hostelCategory) {
                $q->where('category_id', $hostelCategory->id);
            })->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count() : 0;
            $hostelData[] = $hostelCount;

            // All facilities bookings
            $facilitiesCount = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $facilitiesData[] = $facilitiesCount;

            // Rental bookings
            $rentalCount = $rentalCategory ? Booking::whereHas('facility', function($q) use ($rentalCategory) {
                $q->where('category_id', $rentalCategory->id);
            })->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count() : 0;
            $rentalData[] = $rentalCount;

            // Commercial bookings
            $commercialCount = $commercialCategory ? Booking::whereHas('facility', function($q) use ($commercialCategory) {
                $q->where('category_id', $commercialCategory->id);
            })->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count() : 0;
            $commercialData[] = $commercialCount;
        }

        return [
            'lineChart' => [
                'monthly' => [
                    'labels' => $labels,
                    'hostel' => $hostelData,
                    'facilities' => $facilitiesData,
                    'rental' => $rentalData,
                    'commercial' => $commercialData,
                ],
                'quarterly' => $this->aggregateToQuarterly($labels, $hostelData, $facilitiesData, $rentalData, $commercialData),
                'yearly' => $this->aggregateToYearly($labels, $hostelData, $facilitiesData, $rentalData, $commercialData),
            ],
            'pieChart' => [
                'monthly' => [$this->sumArray($hostelData), $this->sumArray($facilitiesData), $this->sumArray($rentalData), $this->sumArray($commercialData)],
                'quarterly' => $this->getQuarterlyTotals($hostelData, $facilitiesData, $rentalData, $commercialData),
                'yearly' => [$this->sumArray($hostelData), $this->sumArray($facilitiesData), $this->sumArray($rentalData), $this->sumArray($commercialData)],
            ],
        ];
    }

    private function aggregateToQuarterly($labels, $hostel, $facilities, $rental, $commercial)
    {
        $quarters = [];
        $hostelQuarters = [];
        $facilitiesQuarters = [];
        $rentalQuarters = [];
        $commercialQuarters = [];

        for ($i = 0; $i < 12; $i += 3) {
            $quarterLabel = 'Q' . (floor($i / 3) + 1) . ' ' . Carbon::now()->year;
            $quarters[] = $quarterLabel;

            $hostelQuarters[] = array_sum(array_slice($hostel, $i, 3));
            $facilitiesQuarters[] = array_sum(array_slice($facilities, $i, 3));
            $rentalQuarters[] = array_sum(array_slice($rental, $i, 3));
            $commercialQuarters[] = array_sum(array_slice($commercial, $i, 3));
        }

        return [
            'labels' => $quarters,
            'hostel' => $hostelQuarters,
            'facilities' => $facilitiesQuarters,
            'rental' => $rentalQuarters,
            'commercial' => $commercialQuarters,
        ];
    }

    private function aggregateToYearly($labels, $hostel, $facilities, $rental, $commercial)
    {
        return [
            'labels' => [Carbon::now()->year],
            'hostel' => [$this->sumArray($hostel)],
            'facilities' => [$this->sumArray($facilities)],
            'rental' => [$this->sumArray($rental)],
            'commercial' => [$this->sumArray($commercial)],
        ];
    }

    private function getQuarterlyTotals($hostel, $facilities, $rental, $commercial)
    {
        $quarters = [];
        for ($i = 0; $i < 12; $i += 3) {
            $quarters[] = array_sum(array_slice($hostel, $i, 3)) +
                         array_sum(array_slice($facilities, $i, 3)) +
                         array_sum(array_slice($rental, $i, 3)) +
                         array_sum(array_slice($commercial, $i, 3));
        }
        return $quarters;
    }

    private function sumArray($array)
    {
        return array_sum($array);
    }

    private function calculateRevenueStats()
    {
        // Calculate revenue from all bookings (total_amount is a computed attribute)
        $totalRevenue = Booking::all()->sum(function ($booking) {
            return $booking->total_amount;
        });

        $monthlyRevenue = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(function ($booking) {
                return $booking->total_amount;
            });

        $yearlyRevenue = Booking::whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(function ($booking) {
                return $booking->total_amount;
            });

        // Debug logging
        Log::info('Revenue Stats Debug:', [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'yearly_revenue' => $yearlyRevenue,
            'total_bookings' => Booking::count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
        ]);

        return [
            'total' => $totalRevenue ?? 0,
            'monthly' => $monthlyRevenue ?? 0,
            'yearly' => $yearlyRevenue ?? 0,
        ];
    }

    private function calculateOccupancyRates($stats)
    {
        $hostelOccupancy = $stats['hostel_rooms'] > 0
            ? round(($stats['hostel_active'] / $stats['hostel_rooms']) * 100, 1)
            : 0;

        $rentalOccupancy = $stats['rental_spaces'] > 0
            ? round(($stats['rental_active'] / $stats['rental_spaces']) * 100, 1)
            : 0;

        $commercialOccupancy = $stats['commercial_spaces'] > 0
            ? round(($stats['commercial_active'] / $stats['commercial_spaces']) * 100, 1)
            : 0;

        $overallOccupancy = $stats['facilities'] > 0
            ? round(($stats['facilities_active'] / $stats['facilities']) * 100, 1)
            : 0;

        return [
            'hostel' => $hostelOccupancy,
            'rental' => $rentalOccupancy,
            'commercial' => $commercialOccupancy,
            'overall' => $overallOccupancy,
        ];
    }

    private function getDashboardAlerts($pendingBookings)
    {
        $alerts = [];

        if ($pendingBookings > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "You have {$pendingBookings} pending booking" . ($pendingBookings > 1 ? 's' : '') . ' that require approval.',
                'action' => 'Review Bookings',
                'icon' => 'clock',
            ];
        }

        // Check for low occupancy
        $totalFacilities = Facility::where('is_active', true)->count();
        $activeBookings = Booking::where('status', 'approved')->count();
        $occupancyRate = $totalFacilities > 0 ? ($activeBookings / $totalFacilities) * 100 : 0;

        if ($occupancyRate < 30) {
            $alerts[] = [
                'type' => 'info',
                'message' => 'Facility occupancy is below 30%. Consider promotional activities.',
                'action' => 'View Reports',
                'icon' => 'chart-line',
            ];
        }

        // Check for upcoming bookings
        $upcomingBookings = Booking::where('status', 'approved')
            ->where('check_in', '>=', Carbon::now())
            ->where('check_in', '<=', Carbon::now()->addDays(7))
            ->count();

        if ($upcomingBookings > 0) {
            $alerts[] = [
                'type' => 'success',
                'message' => "{$upcomingBookings} booking" . ($upcomingBookings > 1 ? 's' : '') . ' scheduled for the next 7 days.',
                'action' => 'View Calendar',
                'icon' => 'calendar',
            ];
        }

        return $alerts;
    }

    public function approveBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully',
            'booking' => $booking
        ]);
    }

    public function cancelBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'booking' => $booking
        ]);
    }

    public function viewBooking($bookingId)
    {
        $booking = Booking::with(['facility.category', 'userClient'])
            ->findOrFail($bookingId);

        return response()->json([
            'booking' => [
                'id' => $booking->id,
                'facility_name' => $booking->facility->name,
                'category' => $booking->facility->category->name ?? 'Unknown',
                'user_name' => $booking->userClient->name ?? 'N/A',
                'user_email' => $booking->userClient->email ?? 'N/A',
                'booking_date' => $booking->check_in ? $booking->check_in->format('Y-m-d') : $booking->created_at->format('Y-m-d'),
                'check_out' => $booking->check_out ? $booking->check_out->format('Y-m-d') : null,
                'duration' => $booking->duration ?? 1,
                'total_amount' => $booking->total_amount ?? 0,
                'status' => $booking->status,
                'notes' => $booking->notes ?? '',
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function approveAllPending(Request $request)
    {
        $pendingBookings = Booking::where('status', 'pending')->get();
        $count = $pendingBookings->count();

        foreach ($pendingBookings as $booking) {
            $booking->update(['status' => 'approved']);
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} pending bookings approved successfully"
        ]);
    }

    public function getBookings(Request $request)
    {
        $query = Booking::with(['facility.category', 'userClient'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('facility', function($facilityQuery) use ($search) {
                    $facilityQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('userClient', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && !empty($request->date)) {
            $date = $request->date;
            $query->where(function($q) use ($date) {
                $q->whereDate('check_in', '<=', $date)
                  ->whereDate('check_out', '>=', $date);
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $bookings = $query->paginate($perPage);

        $formattedBookings = $bookings->getCollection()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'category' => $booking->facility->category->name ?? 'Unknown',
                'name' => $booking->facility->name,
                'date' => $booking->created_at->format('Y-m-d'),
                'status' => $booking->status,
                'user_name' => $booking->userClient->name ?? 'N/A',
                'user_email' => $booking->userClient->email ?? 'N/A',
                'booking_date' => $booking->check_in ? $booking->check_in->format('Y-m-d') : $booking->created_at->format('Y-m-d'),
                'duration' => $booking->duration ?? 1,
                'total_amount' => $booking->total_amount ?? 0,
            ];
        });

        return response()->json([
            'bookings' => $formattedBookings,
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ]
        ]);
    }

    public function getBookingsForCalendar(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = Booking::with(['facility.category', 'userClient'])
            ->where('status', 'approved');

        if ($start && $end) {
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('check_in', [$start, $end])
                  ->orWhereBetween('check_out', [$start, $end])
                  ->orWhere(function($subQ) use ($start, $end) {
                      $subQ->where('check_in', '<=', $start)
                           ->where('check_out', '>=', $end);
                  });
            });
        }

        $bookings = $query->get();

        $events = [];
        foreach ($bookings as $booking) {
            $checkIn = $booking->check_in ? Carbon::parse($booking->check_in) : null;
            $checkOut = $booking->check_out ? Carbon::parse($booking->check_out) : null;

            if ($checkIn && $checkOut) {
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->facility->name . ' - ' . ($booking->userClient->name ?? 'N/A'),
                    'start' => $checkIn->toDateString(),
                    'end' => $checkOut->addDay()->toDateString(), // FullCalendar expects end date to be exclusive
                    'backgroundColor' => $this->getStatusColor($booking->status),
                    'borderColor' => $this->getStatusColor($booking->status),
                    'extendedProps' => [
                        'bookingId' => $booking->id,
                        'facility' => $booking->facility->name,
                        'user' => $booking->userClient->name ?? 'N/A',
                        'status' => $booking->status,
                        'amount' => $booking->total_amount ?? 0,
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'approved': return '#22C55E'; // green
            case 'pending': return '#F97316'; // orange
            case 'cancelled': return '#EF4444'; // red
            default: return '#6B7280'; // gray
        }
    }
}
