<?php

namespace App\Http\Controllers\PayPark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class PayParkDashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $availableYears = DB::table('paypark_transactions')
            ->selectRaw('DISTINCT YEAR(transaction_date) as year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        $mode = $request->query('mode') ?: $this->determineMode($year, $month, $startDate, $endDate, $availableYears);

        [$chart, $dateRange] = $this->buildChartData($mode, $year, $month, $startDate, $endDate, $availableYears);
        $stats = $this->calculateStats($dateRange['start'], $dateRange['end']);
        $hoverBars = $this->buildHoverBarsFromSeries($chart['labels'], $chart['data']);

        // 🔹 Audit log for dashboard view
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Viewed Admin Dashboard',
            'details'    => 'Mode: ' . $mode . ', Date range: ' . $dateRange['start']->toDateString() . ' to ' . $dateRange['end']->toDateString(),
            'ip_address' => $request->ip(),
        ]);
        
        $logs = AuditLog::latest()->take(10)->get(); 

        return inertia('PayToPark/admin_Dashboard', [
            'totalVehicles' => $stats['totalVehicles'],
            'totalIncome' => number_format($stats['totalIncome'], 0),
            'peakHours' => $stats['peakHours'],
            'avgParkingTime' => $stats['avgParkingTime'],
            'returningClients' => $stats['returningClientsPercent'],
            'newUsersThisMonth' => $stats['newUsers'],
            'chart' => $chart,
            'months' => $hoverBars,
            'admin' => Auth::guard('admin')->user(),
            'filters' => [
                'mode' => $mode,
                'year' => $year ? (int) $year : null,
                'month' => $month ? (int) $month : null,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'availableYears' => $availableYears,
                'logs' => $logs, // ✅ pass logs here
            ]
        ]);
          
    }

    private function determineMode($year, $month, $startDate, $endDate, $availableYears)
    {
        if ($startDate && $endDate) {
            return 'custom';
        }
        if (!$year && count($availableYears) > 1 && !$startDate && !$endDate) {
            return 'multi-year';
        }
        if ($year && !$month) {
            return 'monthly';
        }
        if ($year && $month) {
            return 'daily';
        }
        if ($year) {
            return 'monthly';
        }
        return 'multi-year';
    }

    private function buildChartData($mode, $year, $month, $startDate, $endDate, $availableYears)
    {
        $labels = [];
        $datasets = [];
        $unit = 'day';
        $title = '';
        $start = null;
        $end = null;

        if ($mode === 'multi-year') {
            sort($availableYears);
            $labels = $availableYears;
            $data = [];
            foreach ($availableYears as $yr) {
                $total = (float)DB::table('paypark_transactions')
                    ->whereYear('transaction_date', $yr)
                    ->sum('total_payment');
                $data[] = $total;
            }
            $datasets[] = [
                'label' => 'Total Income',
                'data' => $data,
            ];
            $unit = 'year';
            $title = 'Total Income by Year';
            $start = Carbon::create(min($availableYears), 1, 1)->startOfDay();
            $end = Carbon::create(max($availableYears), 12, 31)->endOfDay();
        } elseif ($mode === 'monthly' && $year) {
            $year = (int) $year;
            $labels = range(1, 12);

            // Monthly totals for that year
            $monthlyTotals = $this->getYearTotals($year);

            // Prepare dataset to compare each month
            $datasets[] = [
                'label' => (string) $year,
                'data' => $monthlyTotals,
            ];

            $unit = 'month';
            $title = "Monthly Income — {$year}";
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end = Carbon::create($year, 12, 31)->endOfDay();
        } elseif ($mode === 'daily' && $year && $month) {
            $year = (int) $year;
            $month = (int) $month;
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $labels = range(1, $daysInMonth);

            // Daily totals for the selected month/year
            $dailyTotals = $this->getMonthTotals($year, $month);

            $unit = 'day';
            $title = "Daily Income — " . Carbon::create($year, $month, 1)->format('F Y');
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = Carbon::create($year, $month, $daysInMonth)->endOfDay();

            $datasets[] = [
                'label' => Carbon::create($year, $month, 1)->format('F Y'),
                'data' => $dailyTotals,
            ];
        } elseif ($mode === 'custom' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            $labels = $this->getDateRangeLabels($startDate, $endDate);
            $data = $this->getRangeTotals($startDate, $endDate);

            $unit = 'day';
            $title = "Income — " . $startDate->format('M d, Y') . " to " . $endDate->format('M d, Y');
            $start = $startDate;
            $end = $endDate;

            $datasets[] = [
                'label' => 'Total Income',
                'data' => $data,
            ];
        } else {
            // Fallback multi-year view if invalid params
            return $this->buildChartData('multi-year', null, null, null, null, $availableYears);
        }

        return [
            [
                'labels' => $labels,
                'datasets' => $datasets,
                'unit' => $unit,
                'title' => $title,
                'data' => $datasets[0]['data'] ?? [], // Convenience
            ],
            ['start' => $start, 'end' => $end]
        ];
    }

    private function getYearTotals($year)
    {
        $raw = DB::table('paypark_transactions')
            ->selectRaw('MONTH(transaction_date) AS month_num, SUM(total_payment) AS total')
            ->whereYear('transaction_date', $year)
            ->groupBy('month_num')
            ->pluck('total', 'month_num');

        return collect(range(1, 12))->map(fn($m) => (float) ($raw[$m] ?? 0))->toArray();
    }

    private function getMonthTotals($year, $month)
    {
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $raw = DB::table('paypark_transactions')
            ->selectRaw('DAY(transaction_date) as day_num, SUM(total_payment) as total')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->groupBy('day_num')
            ->pluck('total', 'day_num');

        return collect(range(1, $daysInMonth))->map(fn($d) => (float) ($raw[$d] ?? 0))->toArray();
    }

    private function getRangeTotals($start, $end)
    {
        $dates = collect();
        $cursor = Carbon::parse($start)->copy();
        $endC = Carbon::parse($end)->copy();
        while ($cursor->lte($endC)) {
            $dates->push($cursor->format('Y-m-d'));
            $cursor->addDay();
        }

        $raw = DB::table('paypark_transactions')
            ->selectRaw('DATE(transaction_date) as d, SUM(total_payment) as total')
            ->whereBetween('transaction_date', [$start, $end])
            ->groupBy('d')
            ->pluck('total', 'd');

        return $dates->map(fn($d) => (float) ($raw[$d] ?? 0))->toArray();
    }

    private function getDateRangeLabels($start, $end)
    {
        $labels = [];
        $cursor = Carbon::parse($start)->copy();
        $endC = Carbon::parse($end)->copy();
        while ($cursor->lte($endC)) {
            $labels[] = $cursor->format('M d');
            $cursor->addDay();
        }
        return $labels;
    }

    private function calculateStats($start, $end)
    {
        $totalVehicles = DB::table('pay_park_clients')
            ->whereBetween('time_in', [$start, $end])
            ->count();

        $totalIncome = (float) DB::table('paypark_transactions')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('total_payment');

        $peakHourRow = DB::table('pay_park_clients')
            ->selectRaw('HOUR(time_in) as hour_in, COUNT(*) as count')
            ->whereBetween('time_in', [$start, $end])
            ->groupBy('hour_in')
            ->orderByDesc('count')
            ->first();

        $peakHours = $peakHourRow
            ? sprintf("%02d:00 - %02d:00", $peakHourRow->hour_in, ($peakHourRow->hour_in + 2) % 24)
            : 'N/A';

        $avgParkingTime = round(
            (float) DB::table('pay_park_clients')
                ->whereBetween('time_in', [$start, $end])
                ->whereNotNull('time_out')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out)) as avg_minutes')
                ->value('avg_minutes') ?? 0
        );

        $returningClientsCount = DB::table('pay_park_clients')
            ->select('name')
            ->whereBetween('time_in', [$start, $end])
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $totalUniqueClients = DB::table('pay_park_clients')
            ->whereBetween('time_in', [$start, $end])
            ->distinct('name')
            ->count('name');

        $returningClientsPercent = $totalUniqueClients > 0
            ? round(($returningClientsCount / $totalUniqueClients) * 100)
            : 0;

        $newUsers = DB::table('pay_park_clients')
            ->whereBetween('created_at', [$start, $end])
            ->distinct('name')
            ->count('name');

        return compact('totalVehicles', 'totalIncome', 'peakHours', 'avgParkingTime', 'returningClientsPercent', 'newUsers');
    }

    private function buildHoverBarsFromSeries($labels, $data)
    {
        $maxRevenue = max(!empty($data) ? $data : [1]);
        $items = [];
        foreach ($labels as $i => $label) {
            $val = (float) ($data[$i] ?? 0);
            $height = $maxRevenue > 0
                ? round(($val / $maxRevenue) * 100, 2) . '%'
                : '0%';
            $items[] = [
                'name' => (string) $label,
                'height' => $height,
                'revenue' => number_format($val, 0),
            ];
        }
        return $items;
    }
}
