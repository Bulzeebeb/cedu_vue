<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Get current month's start and end dates
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Current month total sales (only from Completed orders)
            $totalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->where('orders.status', 'Completed') // Only count completed orders
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Current month crop sales (fruits + vegetables, Completed only)
            $cropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->where('orders.status', 'Completed')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Current month poultry sales (Completed only)
            $poultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->where('orders.status', 'Completed')
                ->where('products.category', 'poultry')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalOrders = Order::count();
            // Count both "Completed" and legacy "paid" status as completed
            $completedOrders = Order::whereIn('status', ['Completed', 'paid'])->count();
            // Count both "Pending" and legacy "pending" status as pending
            $pendingOrders = Order::whereIn('status', ['Pending', 'pending'])->count();

            // Previous week calculations for growth comparison
            $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
            $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
            $thisWeekStart = Carbon::now()->startOfWeek();

            // Last week total sales (Completed only)
            $lastWeekTotalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // This week total sales (Completed only)
            $thisWeekTotalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Last week crop sales (Completed only)
            $lastWeekCropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('orders.status', 'Completed')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // This week crop sales (Completed only)
            $thisWeekCropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->where('orders.status', 'Completed')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Last week poultry sales (Completed only)
            $lastWeekPoultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // This week poultry sales (Completed only)
            $thisWeekPoultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Calculate growth percentages
            $totalSalesGrowth = null;
            $cropSalesGrowth = null;
            $poultrySalesGrowth = null;

            if ($lastWeekTotalSales > 0) {
                $totalSalesGrowth = round((($thisWeekTotalSales - $lastWeekTotalSales) / $lastWeekTotalSales) * 100, 1);
            }

            if ($lastWeekCropSales > 0) {
                $cropSalesGrowth = round((($thisWeekCropSales - $lastWeekCropSales) / $lastWeekCropSales) * 100, 1);
            }

            if ($lastWeekPoultrySales > 0) {
                $poultrySalesGrowth = round((($thisWeekPoultrySales - $lastWeekPoultrySales) / $lastWeekPoultrySales) * 100, 1);
            }

            // Update order_date for records that don't have it
            Order::whereNull('order_date')
                ->update(['order_date' => DB::raw('created_at')]);

            // Get recent orders
            $recentOrders = Order::latest('created_at')
                ->with('orderItems')
                ->select('id', 'first_name', 'last_name', 'total_amount', 'created_at', 'order_date', 'status')
                ->take(10)
                ->get()
                ->map(function ($order) {
                    $order->formatted_created_at = $order->created_at?->format('Y-m-d H:i:s');
                    $order->formatted_order_date = $order->order_date?->format('Y-m-d H:i:s');
                    $order->display_date = ($order->order_date && $order->order_date->format('H:i:s') !== '00:00:00')
                        ? $order->order_date
                        : $order->created_at;
                    return $order;
                });

            // Chart data calculations
            $chartData = $this->getChartData();

            return Inertia::render('OnlineMarket_ADMIN/adminDashboard', [
                'stats' => [
                    'total_sales' => floatval($totalSales),
                    'crop_sales' => floatval($cropSales),
                    'poultry_sales' => floatval($poultrySales),
                    'completed_orders' => $completedOrders,
                    'pending_orders' => $pendingOrders,
                    'total_orders' => $totalOrders,
                    'total_sales_growth' => $totalSalesGrowth,
                    'crop_sales_growth' => $cropSalesGrowth,
                    'poultry_sales_growth' => $poultrySalesGrowth,
                ],
                'recentOrders' => $recentOrders,
                'chartData' => $chartData,
                'admin' => $admin,
                'month' => Carbon::now()->format('F Y')
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return Inertia::render('OnlineMarket_ADMIN/adminDashboard', [
                'stats' => [
                    'total_sales' => 0,
                    'crop_sales' => 0,
                    'poultry_sales' => 0,
                    'completed_orders' => 0,
                    'pending_orders' => 0,
                    'total_orders' => 0,
                    'total_sales_growth' => null,
                    'crop_sales_growth' => null,
                    'poultry_sales_growth' => null,
                ],
                'recentOrders' => collect([]),
                'chartData' => [
                    'lineChart' => [
                        'monthly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                        'quarterly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                        'yearly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                    ],
                    'pieChart' => [
                        'monthly' => [0, 0, 0],
                        'quarterly' => [0, 0, 0],
                        'yearly' => [0, 0, 0],
                        'categoryLabels' => ['Category 1', 'Category 2', 'Category 3']
                    ]
                ],
            ]);
        }
    }

    private function getChartData()
    {
        try {
            $monthlyData = $this->getMonthlyChartData();
            $quarterlyData = $this->getQuarterlyChartData();
            $yearlyData = $this->getYearlyChartData();
            $categoryData = $this->getCategoryBreakdownData();

            return [
                'lineChart' => [
                    'monthly' => $monthlyData,
                    'quarterly' => $quarterlyData,
                    'yearly' => $yearlyData,
                ],
                'pieChart' => [
                    'monthly' => $categoryData['monthly'],
                    'quarterly' => $categoryData['quarterly'],
                    'yearly' => $categoryData['yearly'],
                    'categoryLabels' => $categoryData['categoryLabels']
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Chart data error: ' . $e->getMessage());
            return [
                'lineChart' => [
                    'monthly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                    'quarterly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                    'yearly' => ['labels' => [], 'totalSales' => [], 'cropSales' => [], 'poultrySales' => []],
                ],
                'pieChart' => [
                    'monthly' => [0, 0, 0],
                    'quarterly' => [0, 0, 0],
                    'yearly' => [0, 0, 0],
                    'categoryLabels' => ['Category 1', 'Category 2', 'Category 3']
                ]
            ];
        }
    }

    private function getMonthlyChartData()
    {
        $days = [];
        $totalSales = [];
        $cropSales = [];
        $poultrySales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');

            // Only count Completed orders
            $dailyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $dailyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $dailyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($dailyTotal ?? 0);
            $cropSales[] = floatval($dailyCrop ?? 0);
            $poultrySales[] = floatval($dailyPoultry ?? 0);
        }

        return [
            'labels' => $days,
            'totalSales' => $totalSales,
            'cropSales' => $cropSales,
            'poultrySales' => $poultrySales,
        ];
    }

    private function getQuarterlyChartData()
    {
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $currentYear = Carbon::now()->year;
        $totalSales = [];
        $cropSales = [];
        $poultrySales = [];

        for ($q = 1; $q <= 4; $q++) {
            $startMonth = ($q - 1) * 3 + 1;
            $endMonth = $q * 3;
            $quarterStart = Carbon::create($currentYear, $startMonth, 1)->startOfMonth();
            $quarterEnd = Carbon::create($currentYear, $endMonth, 1)->endOfMonth();

            $quarterlyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $quarterlyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $quarterlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($quarterlyTotal ?? 0);
            $cropSales[] = floatval($quarterlyCrop ?? 0);
            $poultrySales[] = floatval($quarterlyPoultry ?? 0);
        }

        return [
            'labels' => $quarters,
            'totalSales' => $totalSales,
            'cropSales' => $cropSales,
            'poultrySales' => $poultrySales,
        ];
    }

    private function getYearlyChartData()
    {
        $years = [];
        $totalSales = [];
        $cropSales = [];
        $poultrySales = [];

        for ($i = 3; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $years[] = (string) $year;
            $yearStart = Carbon::create($year, 1, 1)->startOfYear();
            $yearEnd = Carbon::create($year, 12, 31)->endOfYear();

            $yearlyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $yearlyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $yearlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->where('orders.status', 'Completed')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($yearlyTotal ?? 0);
            $cropSales[] = floatval($yearlyCrop ?? 0);
            $poultrySales[] = floatval($yearlyPoultry ?? 0);
        }

        return [
            'labels' => $years,
            'totalSales' => $totalSales,
            'cropSales' => $cropSales,
            'poultrySales' => $poultrySales,
        ];
    }

    private function getCategoryBreakdownData()
    {
        try {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $currentQuarter = ceil($currentMonth / 3);

            $availableCategories = DB::table('products')
                ->select('category')
                ->distinct()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->pluck('category')
                ->toArray();

            if (empty($availableCategories)) {
                Log::warning('No categories found in products table');
                return [
                    'monthly' => [0, 0, 0],
                    'quarterly' => [0, 0, 0],
                    'yearly' => [0, 0, 0],
                    'categoryLabels' => ['No Data', 'No Data', 'No Data']
                ];
            }

            // Monthly breakdown (Completed orders only)
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd = Carbon::now()->endOfMonth();

            $monthlyData = [];
            foreach ($availableCategories as $category) {
                $monthlyAmount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category', $category)
                    ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
                    ->where('orders.status', 'Completed')
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $monthlyData[$category] = floatval($monthlyAmount ?? 0);
            }

            // Quarterly breakdown (Completed orders only)
            $quarterStartMonth = ($currentQuarter - 1) * 3 + 1;
            $quarterEndMonth = $currentQuarter * 3;
            $quarterStart = Carbon::create($currentYear, $quarterStartMonth, 1)->startOfMonth();
            $quarterEnd = Carbon::create($currentYear, $quarterEndMonth, 1)->endOfMonth();

            $quarterlyData = [];
            foreach ($availableCategories as $category) {
                $quarterlyAmount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category', $category)
                    ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                    ->where('orders.status', 'Completed')
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $quarterlyData[$category] = floatval($quarterlyAmount ?? 0);
            }

            // Yearly breakdown (Completed orders only)
            $yearStart = Carbon::now()->startOfYear();
            $yearEnd = Carbon::now()->endOfYear();

            $yearlyData = [];
            foreach ($availableCategories as $category) {
                $yearlyAmount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category', $category)
                    ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                    ->where('orders.status', 'Completed')
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $yearlyData[$category] = floatval($yearlyAmount ?? 0);
            }

            $categories = array_pad(array_slice($availableCategories, 0, 3), 3, 'Other');

            return [
                'monthly' => [
                    floatval($monthlyData[$categories[0]] ?? 0),
                    floatval($monthlyData[$categories[1]] ?? 0),
                    floatval($monthlyData[$categories[2]] ?? 0)
                ],
                'quarterly' => [
                    floatval($quarterlyData[$categories[0]] ?? 0),
                    floatval($quarterlyData[$categories[1]] ?? 0),
                    floatval($quarterlyData[$categories[2]] ?? 0)
                ],
                'yearly' => [
                    floatval($yearlyData[$categories[0]] ?? 0),
                    floatval($yearlyData[$categories[1]] ?? 0),
                    floatval($yearlyData[$categories[2]] ?? 0)
                ],
                'categoryLabels' => $categories
            ];

        } catch (\Exception $e) {
            Log::error('Category breakdown error: ' . $e->getMessage());
            return [
                'monthly' => [0, 0, 0],
                'quarterly' => [0, 0, 0],
                'yearly' => [0, 0, 0],
                'categoryLabels' => ['Category 1', 'Category 2', 'Category 3']
            ];
        }
    }
}
