<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Current period calculations
        $totalSales = DB::table('order_items')->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $cropSales = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('products.category', ['fruit', 'vegetable'])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $poultrySales = DB::table('order_items')
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
            ->whereIn('products.category', ['fruit', 'vegetable'])
            ->whereBetween('orders.created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $thisWeekCropSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('products.category', ['fruit', 'vegetable'])
            ->where('orders.created_at', '>=', $thisWeekStart)
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

        // Recent Orders with their items
        $recentOrders = Order::latest()
            ->with('orderItems')
            ->take(10)
            ->get();

        // Chart data calculations
        $chartData = $this->getChartData();

        return Inertia::render('OnlineMarket_ADMIN/adminDashboard', [
            'stats' => [
                'total_sales' => $totalSales,
                'crop_sales' => $cropSales,
                'poultry_sales' => $poultrySales,
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
        ]);
    }

    private function getChartData()
    {
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
            ]
        ];
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
                ->whereIn('products.category', ['fruit', 'vegetable'])
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Poultry sales for this day
            $dailyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($dailyTotal);
            $cropSales[] = floatval($dailyCrop);
            $poultrySales[] = floatval($dailyPoultry);
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
                ->whereIn('products.category', ['fruit', 'vegetable'])
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Poultry sales for this quarter
            $quarterlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($quarterlyTotal);
            $cropSales[] = floatval($quarterlyCrop);
            $poultrySales[] = floatval($quarterlyPoultry);
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
            $years[] = (string)$year;

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
                ->whereIn('products.category', ['fruit', 'vegetable'])
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            // Poultry sales for this year
            $yearlyPoultry = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.category', 'poultry')
                ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
                ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

            $totalSales[] = floatval($yearlyTotal);
            $cropSales[] = floatval($yearlyCrop);
            $poultrySales[] = floatval($yearlyPoultry);
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
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentQuarter = ceil($currentMonth / 3);

        // Monthly breakdown (current month)
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthlyFruits = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'fruit')
            ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $monthlyVegetables = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'vegetable')
            ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $monthlyPoultry = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'poultry')
            ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        // Quarterly breakdown (current quarter)
        $quarterStartMonth = ($currentQuarter - 1) * 3 + 1;
        $quarterEndMonth = $currentQuarter * 3;
        $quarterStart = Carbon::create($currentYear, $quarterStartMonth, 1)->startOfMonth();
        $quarterEnd = Carbon::create($currentYear, $quarterEndMonth, 1)->endOfMonth();

        $quarterlyFruits = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'fruit')
            ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $quarterlyVegetables = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'vegetable')
            ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $quarterlyPoultry = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'poultry')
            ->whereBetween('orders.created_at', [$quarterStart, $quarterEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        // Yearly breakdown (current year)
        $yearStart = Carbon::now()->startOfYear();
        $yearEnd = Carbon::now()->endOfYear();

        $yearlyFruits = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'fruit')
            ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $yearlyVegetables = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'vegetable')
            ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        $yearlyPoultry = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.category', 'poultry')
            ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        return [
            'monthly' => [floatval($monthlyFruits), floatval($monthlyVegetables), floatval($monthlyPoultry)],
            'quarterly' => [floatval($quarterlyFruits), floatval($quarterlyVegetables), floatval($quarterlyPoultry)],
            'yearly' => [floatval($yearlyFruits), floatval($yearlyVegetables), floatval($yearlyPoultry)],
        ];
    }
}
