<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        try {
            Log::info('=== SuperAdmin Dashboard: Starting data fetch ===');
            
            // Debug: Check table existence and data
            $ordersCount = DB::table('orders')->count();
            $payparkCount = DB::table('paypark_transactions')->count();
            $bookingsCount = DB::table('bookings')->count();
            
            Log::info("Table counts - Orders: $ordersCount, PayPark: $payparkCount, Bookings: $bookingsCount");
            
            // Get all sales data
            $cropSales = (float) DB::table('orders')
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            $payparkSales = (float) DB::table('paypark_transactions')
                ->sum('total_amount');

            $rentalSales = (float) DB::table('bookings')
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');

            Log::info("Sales - Crop: $cropSales, PayPark: $payparkSales, Rental: $rentalSales");
            
            // Log some sample data
            $sampleOrder = DB::table('orders')->first();
            $samplePaypark = DB::table('paypark_transactions')->first();
            $sampleBooking = DB::table('bookings')->first();
            
            Log::info('Sample Order: ' . json_encode($sampleOrder));
            Log::info('Sample PayPark: ' . json_encode($samplePaypark));
            Log::info('Sample Booking: ' . json_encode($sampleBooking));

            // Facilities Stats
            $totalFacilities = Facility::count();
            $activeFacilities = Facility::where('status', 'active')->count();
            $facilityBookings = Booking::count();
            $confirmedBookings = Booking::whereIn('status', ['confirmed', 'approved'])->count();

            Log::info("Facilities - Total: $totalFacilities, Active: $activeFacilities, Bookings: $facilityBookings, Confirmed: $confirmedBookings");

            // Calculate growth (this week vs last week)
            $thisWeekStart = Carbon::now()->startOfWeek();
            $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
            $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

            $lastWeekCropSales = DB::table('orders')
                ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0;

            $thisWeekCropSales = DB::table('orders')
                ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0;

            $lastWeekPayparkSales = DB::table('paypark_transactions')
                ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->sum('total_amount') ?? 0;

            $thisWeekPayparkSales = DB::table('paypark_transactions')
                ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
                ->sum('total_amount') ?? 0;

            $lastWeekRentalSales = DB::table('bookings')
                ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_price') ?? 0;

            $thisWeekRentalSales = DB::table('bookings')
                ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total_price') ?? 0;

            // Calculate percentages
            $cropSalesGrowth = $this->calculateGrowth($thisWeekCropSales, $lastWeekCropSales);
            $payparkSalesGrowth = $this->calculateGrowth($thisWeekPayparkSales, $lastWeekPayparkSales);
            $rentalSalesGrowth = $this->calculateGrowth($thisWeekRentalSales, $lastWeekRentalSales);

            $totalLastWeek = $lastWeekCropSales + $lastWeekPayparkSales + $lastWeekRentalSales;
            $totalThisWeek = $thisWeekCropSales + $thisWeekPayparkSales + $thisWeekRentalSales;
            $totalSalesGrowth = $this->calculateGrowth($totalThisWeek, $totalLastWeek);

            // Recent Orders
            $recentOrders = DB::table('orders')
                ->select('id', 'first_name', 'last_name', 'total_amount', 'created_at', 'status')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
            
            Log::info('Recent Orders Count: ' . count($recentOrders));
            Log::info('Recent Orders: ' . json_encode($recentOrders));

            // Recent Bookings
            $recentBookings = DB::table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.id')
                ->join('user_client', 'bookings.user_id', '=', 'user_client.id')
                ->select(
                    'bookings.id',
                    'bookings.status',
                    'bookings.created_at',
                    'facilities.name as facility_name',
                    'user_client.first_name',
                    'user_client.last_name'
                )
                ->orderByDesc('bookings.created_at')
                ->limit(10)
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'status' => $booking->status,
                        'created_at' => $booking->created_at,
                        'facility' => ['name' => $booking->facility_name],
                        'user' => ['first_name' => $booking->first_name, 'last_name' => $booking->last_name]
                    ];
                });
            
            Log::info('Recent Bookings Count: ' . count($recentBookings));
            Log::info('Recent Bookings: ' . json_encode($recentBookings));

            // Chart Data
            $chartData = $this->getChartData();
            
            Log::info('Chart Data: ' . json_encode($chartData));

            $responseData = [
                'stats' => [
                    'total_sales' => floatval($cropSales + $payparkSales + $rentalSales),
                    'crop_sales' => floatval($cropSales),
                    'paypark_sales' => floatval($payparkSales),
                    'rental_sales' => floatval($rentalSales),
                    'total_facilities' => $totalFacilities,
                    'active_facilities' => $activeFacilities,
                    'facility_bookings' => $facilityBookings,
                    'confirmed_bookings' => $confirmedBookings,
                    'total_sales_growth' => $this->calculateGrowth($this->getThisWeekSales(), $this->getLastWeekSales()),
                    'crop_sales_growth' => $this->calculateGrowth($this->getThisWeekCropSales(), $this->getLastWeekCropSales()),
                    'paypark_sales_growth' => $this->calculateGrowth($this->getThisWeekPayparkSales(), $this->getLastWeekPayparkSales()),
                    'rental_sales_growth' => $this->calculateGrowth($this->getThisWeekRentalSales(), $this->getLastWeekRentalSales()),
                ],
                'recentOrders' => $recentOrders,
                'recentBookings' => $recentBookings,
                'chartData' => $chartData,
            ];
            
            Log::info('Final Response Data: ' . json_encode($responseData));

            return Inertia::render('SuperAdminFinal/adminDashboard', $responseData);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return Inertia::render('SuperAdminFinal/adminDashboard', [
                'stats' => [
                    'total_sales' => 0,
                    'crop_sales' => 0,
                    'paypark_sales' => 0,
                    'rental_sales' => 0,
                    'total_facilities' => 0,
                    'active_facilities' => 0,
                    'facility_bookings' => 0,
                    'confirmed_bookings' => 0,
                    'total_sales_growth' => null,
                    'crop_sales_growth' => null,
                    'paypark_sales_growth' => null,
                    'rental_sales_growth' => null,
                ],
                'recentOrders' => [],
                'recentBookings' => [],
                'chartData' => $this->getEmptyChartData(),
            ]);
        }
    }

    private function getLastWeekSales()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        
        $crops = DB::table('orders')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
            
        $paypark = DB::table('paypark_transactions')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('total_amount') ?? 0;
            
        $rental = DB::table('bookings')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0;
            
        return floatval($crops + $paypark + $rental);
    }

    private function getThisWeekSales()
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        
        $crops = DB::table('orders')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
            
        $paypark = DB::table('paypark_transactions')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->sum('total_amount') ?? 0;
            
        $rental = DB::table('bookings')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0;
            
        return floatval($crops + $paypark + $rental);
    }

    private function getLastWeekCropSales()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        return floatval(DB::table('orders')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);
    }

    private function getThisWeekCropSales()
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        return floatval(DB::table('orders')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);
    }

    private function getLastWeekPayparkSales()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        return floatval(DB::table('paypark_transactions')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('total_amount') ?? 0);
    }

    private function getThisWeekPayparkSales()
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        return floatval(DB::table('paypark_transactions')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->sum('total_amount') ?? 0);
    }

    private function getLastWeekRentalSales()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        return floatval(DB::table('bookings')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0);
    }

    private function getThisWeekRentalSales()
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        return floatval(DB::table('bookings')
            ->whereDate('created_at', '>=', $thisWeekStart->toDateString())
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0);
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function getChartData()
    {
        return [
            'lineChart' => [
                'monthly' => $this->getMonthlyChartData(),
                'quarterly' => $this->getQuarterlyChartData(),
                'yearly' => $this->getYearlyChartData(),
            ],
            'pieChart' => [
                'monthly' => $this->getMonthlyCategoryData(),
                'quarterly' => $this->getQuarterlyCategoryData(),
                'yearly' => $this->getYearlyCategoryData(),
            ],
        ];
    }

    private function getMonthlyChartData()
    {
        $now = Carbon::now();
        $startOfMonth = $now->clone()->startOfMonth();
        $endOfMonth = $now->clone()->endOfMonth();

        $days = [];
        $cropSalesData = [];
        $payparkSalesData = [];
        $rentalSalesData = [];

        $daysInMonth = $endOfMonth->day;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $startOfMonth->clone()->addDays($i - 1);

            if ($date->isAfter($now)) {
                break;
            }

            $days[] = $date->format('M d');

            $crops = floatval(DB::table('orders')
                ->whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0);
            $cropSalesData[] = $crops;

            $paypark = floatval(DB::table('paypark_transactions')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount') ?? 0);
            $payparkSalesData[] = $paypark;

            $rental = floatval(DB::table('bookings')
                ->whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total_price') ?? 0);
            $rentalSalesData[] = $rental;
        }

        return [
            'labels' => count($days) > 0 ? $days : ['No data'],
            'cropSales' => count($cropSalesData) > 0 ? $cropSalesData : [0],
            'payparkSales' => count($payparkSalesData) > 0 ? $payparkSalesData : [0],
            'rentalSales' => count($rentalSalesData) > 0 ? $rentalSalesData : [0],
        ];
    }

    private function getQuarterlyChartData()
    {
        $now = Carbon::now();
        $labels = [];
        $cropSalesData = [];
        $payparkSalesData = [];
        $rentalSalesData = [];

        for ($m = 1; $m <= 12; $m += 3) {
            $startDate = Carbon::createFromDate($now->year, $m, 1);
            $endDate = Carbon::createFromDate($now->year, $m + 2, 1)->endOfMonth();

            if ($startDate->isAfter($now)) {
                break;
            }

            $labels[] = 'Q' . ceil($m / 3);

            $crops = floatval(DB::table('orders')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0);
            $cropSalesData[] = $crops;

            $paypark = floatval(DB::table('paypark_transactions')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount') ?? 0);
            $payparkSalesData[] = $paypark;

            $rental = floatval(DB::table('bookings')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total_price') ?? 0);
            $rentalSalesData[] = $rental;
        }

        return [
            'labels' => count($labels) > 0 ? $labels : ['Q1', 'Q2', 'Q3', 'Q4'],
            'cropSales' => count($cropSalesData) > 0 ? $cropSalesData : [0, 0, 0, 0],
            'payparkSales' => count($payparkSalesData) > 0 ? $payparkSalesData : [0, 0, 0, 0],
            'rentalSales' => count($rentalSalesData) > 0 ? $rentalSalesData : [0, 0, 0, 0],
        ];
    }

    private function getYearlyChartData()
    {
        $now = Carbon::now();
        $labels = [];
        $cropSalesData = [];
        $payparkSalesData = [];
        $rentalSalesData = [];

        for ($year = $now->year - 3; $year <= $now->year; $year++) {
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate = Carbon::createFromDate($year, 12, 31);

            if ($startDate->isAfter($now)) {
                break;
            }

            $labels[] = strval($year);

            $crops = floatval(DB::table('orders')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0);
            $cropSalesData[] = $crops;

            $paypark = floatval(DB::table('paypark_transactions')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount') ?? 0);
            $payparkSalesData[] = $paypark;

            $rental = floatval(DB::table('bookings')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total_price') ?? 0);
            $rentalSalesData[] = $rental;
        }

        return [
            'labels' => count($labels) > 0 ? $labels : ['2021', '2022', '2023', '2024'],
            'cropSales' => count($cropSalesData) > 0 ? $cropSalesData : [0, 0, 0, 0],
            'payparkSales' => count($payparkSalesData) > 0 ? $payparkSalesData : [0, 0, 0, 0],
            'rentalSales' => count($rentalSalesData) > 0 ? $rentalSalesData : [0, 0, 0, 0],
        ];
    }

    private function getMonthlyCategoryData()
    {
        $now = Carbon::now();
        $startOfMonth = $now->clone()->startOfMonth();
        $endOfMonth = $now->clone()->endOfMonth();

        $cropSales = floatval(DB::table('orders')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);

        $payparkSales = floatval(DB::table('paypark_transactions')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount') ?? 0);

        $rentalSales = floatval(DB::table('bookings')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0);

        return [$cropSales, $payparkSales, $rentalSales];
    }

    private function getQuarterlyCategoryData()
    {
        $now = Carbon::now();
        $quarter = ceil($now->month / 3);
        $startMonth = ($quarter - 1) * 3 + 1;
        $endMonth = $quarter * 3;

        $startDate = Carbon::createFromDate($now->year, $startMonth, 1);
        $endDate = Carbon::createFromDate($now->year, $endMonth, 1)->endOfMonth();

        $cropSales = floatval(DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);

        $payparkSales = floatval(DB::table('paypark_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount') ?? 0);

        $rentalSales = floatval(DB::table('bookings')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0);

        return [$cropSales, $payparkSales, $rentalSales];
    }

    private function getYearlyCategoryData()
    {
        $now = Carbon::now();
        $startDate = Carbon::createFromDate($now->year, 1, 1);
        $endDate = Carbon::createFromDate($now->year, 12, 31)->endOfYear();

        $cropSales = floatval(DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);

        $payparkSales = floatval(DB::table('paypark_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount') ?? 0);

        $rentalSales = floatval(DB::table('bookings')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price') ?? 0);

        return [$cropSales, $payparkSales, $rentalSales];
    }

    private function getEmptyChartData()
    {
        return [
            'lineChart' => [
                'monthly' => ['labels' => [], 'cropSales' => [], 'payparkSales' => [], 'rentalSales' => []],
                'quarterly' => ['labels' => [], 'cropSales' => [], 'payparkSales' => [], 'rentalSales' => []],
                'yearly' => ['labels' => [], 'cropSales' => [], 'payparkSales' => [], 'rentalSales' => []],
            ],
            'pieChart' => [
                'monthly' => [0, 0, 0],
                'quarterly' => [0, 0, 0],
                'yearly' => [0, 0, 0],
            ],
        ];
    }
}
