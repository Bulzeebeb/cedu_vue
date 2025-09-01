<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class AdminReportsController extends Controller
{
    public function index()
    {

        try {

            $admin = Auth::guard('admin')->user();
            $products = Product::all();
            // Current period calculations

            // Get current month's start and end dates
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            // Current month total sales
            $totalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Current month crop sales (fruits + vegetables)
            $cropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $poultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalOrders = Order::count();
            $completedOrders = Order::where('status', 'paid')->count();
            $pendingOrders = Order::where('status', 'pending')->count();

            // Previous week calculations for growth comparison
            $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
            $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
            $thisWeekStart = Carbon::now()->startOfWeek();

            // Only calculate growth if there's data from previous week
            $lastWeekTotalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $thisWeekTotalSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $lastWeekCropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $thisWeekCropSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));


            $lastWeekPoultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $thisWeekPoultrySales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->where('orders.created_at', '>=', $thisWeekStart)
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Calculate growth percentages only if there's previous data
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

            // Recent Orders with their items - Updated to show proper timestamps
            // Step 1: Update order_date for records that don't have it yet
            Order::whereNull('order_date')
                ->update(['order_date' => DB::raw('created_at')]);

            // Step 2: Get the recent orders
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

            return Inertia::render('OnlineMarket_ADMIN/adminReports', [
                'stats' => [
                    'total_sales' => floatval($totalSales),
                    'crop_sales' => floatval($cropSales),
                    'poultry_sales' => floatval($poultrySales),
                    'completed_orders' => $completedOrders,
                    'pending_orders' => $pendingOrders,
                    'total_orders' => $totalOrders,
                    // Growth percentages (null if no previous data)
                    'total_sales_growth' => $totalSalesGrowth,
                    'crop_sales_growth' => $cropSalesGrowth,
                    'poultry_sales_growth' => $poultrySalesGrowth,
                ],
                'recentOrders' => $recentOrders,
                'chartData' => $chartData,
                'admin' => $admin,
                'products' => $products,
                'month' => Carbon::now()->format('F Y')
            ]);

        } catch (\Exception $e) {
            Log::error('Reports error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return empty data to prevent frontend crash
            return Inertia::render('OnlineMarket_ADMIN/adminReports', [
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
            // Get data for different time periods
            $monthlyData = $this->getMonthlyChartData();
            $quarterlyData = $this->getQuarterlyChartData();
            $yearlyData = $this->getYearlyChartData();

            // Get category breakdown data
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

            // Return empty chart data structure
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
        // Get last 7 days of data
        $days = [];
        $totalSales = [];
        $cropSales = [];
        $poultrySales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D'); // Mon, Tue, Wed, etc.

            // Total sales for this day
            $dailyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Crop sales for this day
            $dailyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));


            // Poultry sales for this day
            $dailyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
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
        // Get last 4 quarters
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

            // Total sales for this quarter
            $quarterlyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Crop sales for this quarter
            $quarterlyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));


            // Poultry sales for this quarter
            $quarterlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
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
        // Get last 4 years
        $years = [];
        $totalSales = [];
        $cropSales = [];
        $poultrySales = [];

        for ($i = 3; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $years[] = (string) $year;

            $yearStart = Carbon::create($year, 1, 1)->startOfYear();
            $yearEnd = Carbon::create($year, 12, 31)->endOfYear();

            // Total sales for this year
            $yearlyTotal = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Crop sales for this year
            $yearlyCrop = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where(function ($query) {
                    $query->whereRaw("LOWER(products.category) LIKE '%fruit%'")
                        ->orWhereRaw("LOWER(products.category) LIKE '%vegetable%'");
                })
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Poultry sales for this year
            $yearlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
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

            // Get all available categories dynamically
            $availableCategories = DB::table('products')
                ->select('category')
                ->distinct()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->pluck('category')
                ->toArray();

            // If no categories found, return default structure
            if (empty($availableCategories)) {
                Log::warning('No categories found in products table');
                return [
                    'monthly' => [0, 0, 0],
                    'quarterly' => [0, 0, 0],
                    'yearly' => [0, 0, 0],
                    'categoryLabels' => ['No Data', 'No Data', 'No Data']
                ];
            }

            Log::info('Available categories:', $availableCategories);

            // Monthly breakdown (current month)
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd = Carbon::now()->endOfMonth();

            // Get monthly data for each category
            $monthlyData = [];
            foreach ($availableCategories as $category) {
                $monthlyAmount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category', $category)
                    ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $monthlyData[$category] = floatval($monthlyAmount ?? 0);
            }

            // Quarterly breakdown (current quarter)
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
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $quarterlyData[$category] = floatval($quarterlyAmount ?? 0);
            }

            // Yearly breakdown (current year)
            $yearStart = Carbon::now()->startOfYear();
            $yearEnd = Carbon::now()->endOfYear();

            $yearlyData = [];
            foreach ($availableCategories as $category) {
                $yearlyAmount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category', $category)
                    ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                    ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

                $yearlyData[$category] = floatval($yearlyAmount ?? 0);
            }

            // Log the data for debugging
            Log::info('Monthly data:', $monthlyData);
            Log::info('Quarterly data:', $quarterlyData);
            Log::info('Yearly data:', $yearlyData);

            // Ensure we have at least 3 categories, pad with 'Other' if needed
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

            // Return safe default data
            return [
                'monthly' => [0, 0, 0],
                'quarterly' => [0, 0, 0],
                'yearly' => [0, 0, 0],
                'categoryLabels' => ['Category 1', 'Category 2', 'Category 3']
            ];
        }
    }
}
