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
        // ✅ Sanitize/normalize inputs
        $mode      = $request->query('mode');      // multi-year | monthly | daily | custom
        $year      = $request->integer('year');
        $month     = $request->integer('month');
        $startDate = $request->query('startDate');
        $endDate   = $request->query('endDate');

        // ✅ Build list of years with data (fallback to current year if empty)
        $availableYears = DB::table('paypark_transactions')
            ->selectRaw('DISTINCT YEAR(transaction_date) AS year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        // ✅ Determine mode if not explicitly given
        $mode = $request->query('mode') ?: $this->determineMode($year, $month, $startDate, $endDate, $availableYears);

        // Harden: if client asks for daily/monthly but forgot params, pick sane defaults
        if ($mode === 'monthly' && !$year) {
            $year = end($availableYears) ?: now()->year; // latest year available
        }
        if ($mode === 'daily') {
            if (!$year)  $year = end($availableYears) ?: now()->year;
            if (!$month) $month = now()->month;
        }

        // ✅ Build chart + date range for KPI stats
        [$chart, $dateRange] = $this->buildChartData($mode, $year, $month, $startDate, $endDate, $availableYears);

        // ✅ Compute KPI stats for the current date range
        $stats = $this->calculateStats($dateRange['start'], $dateRange['end']);

        // ✅ Build hover bars (for your mini bar list if you use it)
        $hoverBars = $this->buildHoverBarsFromSeries($chart['labels'], $chart['data']);

        // 🔹 Optional: Audit log for dashboard view
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Viewed Admin Dashboard',
            'details'    => 'Mode: ' . $mode . ', Date range: ' . $dateRange['start']->toDateString() . ' to ' . $dateRange['end']->toDateString(),
            'ip_address' => $request->ip(),
        ]);

        $logs = AuditLog::latest()->take(10)->get();

        return inertia('PayToPark/admin_Dashboard', [
            // KPIs
            'totalVehicles'     => $stats['totalVehicles'],
            'totalIncome'       => number_format($stats['totalIncome'], 0),
            'peakHours'         => $stats['peakHours'],
            'avgParkingTime'    => $stats['avgParkingTime'],
            'returningClients'  => $stats['returningClientsPercent'],
            'newUsersThisMonth' => $stats['newUsers'],
<<<<<<< Updated upstream

            // Chart + grid bars
            'chart'   => $chart,
            'months'  => $hoverBars,

            // Filters/state sent to frontend
=======
            'chart' => $chart,
            'months' => $hoverBars,
>>>>>>> Stashed changes
            'filters' => [
                'mode'           => $mode,
                'year'           => $year ?: null,
                'month'          => $month ?: null,
                'startDate'      => $startDate,
                'endDate'        => $endDate,
                'availableYears' => $availableYears,
            ],

            // Latest logs (if you want to render somewhere)
            'logs' => $logs,
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
        $labels  = [];
        $datasets = [];
        $unit    = 'day';
        $title   = '';
        $start   = null;
        $end     = null;

        if ($mode === 'multi-year') {
            sort($availableYears);
            $labels = $availableYears;

            $data = [];
            foreach ($availableYears as $yr) {
                $total = (float) DB::table('paypark_transactions')
                    ->whereYear('transaction_date', $yr)
                    ->sum('total_payment');
                $data[] = $total;
            }

            $datasets[] = [
                'label' => 'Total Income',
                'data'  => $data,
            ];

            $unit  = 'year';
            $title = 'Total Income by Year';
            $start = Carbon::create(min($availableYears), 1, 1)->startOfDay();
            $end   = Carbon::create(max($availableYears), 12, 31)->endOfDay();

        } elseif ($mode === 'monthly' && $year) {
            $year   = (int) $year;
            $labels = range(1, 12);

            $monthlyTotals = $this->getYearTotals($year);

            $datasets[] = [
                'label' => (string) $year,
                'data'  => $monthlyTotals,
            ];

            $unit  = 'month';
            $title = "Monthly Income — {$year}";
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end   = Carbon::create($year, 12, 31)->endOfDay();

        } elseif ($mode === 'daily' && $year && $month) {
            $year         = (int) $year;
            $month        = (int) $month;
            $daysInMonth  = Carbon::create($year, $month)->daysInMonth;
            $labels       = range(1, $daysInMonth);

            $dailyTotals = $this->getMonthTotals($year, $month);

            $datasets[] = [
                'label' => Carbon::create($year, $month, 1)->format('F Y'),
                'data'  => $dailyTotals,
            ];

            $unit  = 'day';
            $title = "Daily Income — " . Carbon::create($year, $month, 1)->format('F Y');
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end   = Carbon::create($year, $month, $daysInMonth)->endOfDay();

        } elseif ($mode === 'custom' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate   = Carbon::parse($endDate)->endOfDay();
            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            $labels = $this->getDateRangeLabels($startDate, $endDate);
            $data   = $this->getRangeTotals($startDate, $endDate);

            $datasets[] = [
                'label' => 'Total Income',
                'data'  => $data,
            ];

            $unit  = 'day';
            $title = "Income — " . $startDate->format('M d, Y') . " to " . $endDate->format('M d, Y');
            $start = $startDate;
            $end   = $endDate;

        } else {
            // Fallback to multi-year if params invalid
            return $this->buildChartData('multi-year', null, null, null, null, $availableYears);
        }

        return [
            [
                'labels'   => $labels,
                'datasets' => $datasets,
                'unit'     => $unit,
                'title'    => $title,
                'data'     => $datasets[0]['data'] ?? [],
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

        return collect(range(1, 12))->map(fn($m) => (float)($raw[$m] ?? 0))->toArray();
    }

    private function getMonthTotals($year, $month)
    {
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $raw = DB::table('paypark_transactions')
            ->selectRaw('DAY(transaction_date) AS day_num, SUM(total_payment) AS total')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->groupBy('day_num')
            ->pluck('total', 'day_num');

        return collect(range(1, $daysInMonth))->map(fn($d) => (float)($raw[$d] ?? 0))->toArray();
    }

    private function getRangeTotals($start, $end)
    {
        $dates  = collect();
        $cursor = Carbon::parse($start)->copy();
        $endC   = Carbon::parse($end)->copy();
        while ($cursor->lte($endC)) {
            $dates->push($cursor->format('Y-m-d'));
            $cursor->addDay();
        }

        $raw = DB::table('paypark_transactions')
            ->selectRaw('DATE(transaction_date) AS d, SUM(total_payment) AS total')
            ->whereBetween('transaction_date', [$start, $end])
            ->groupBy('d')
            ->pluck('total', 'd');

        return $dates->map(fn($d) => (float)($raw[$d] ?? 0))->toArray();
    }

    private function getDateRangeLabels($start, $end)
    {
        $labels = [];
        $cursor = Carbon::parse($start)->copy();
        $endC   = Carbon::parse($end)->copy();
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
            ->selectRaw('HOUR(time_in) AS hour_in, COUNT(*) AS count')
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
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out)) AS avg_minutes')
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

        return compact(
            'totalVehicles',
            'totalIncome',
            'peakHours',
            'avgParkingTime',
            'returningClientsPercent',
            'newUsers'
        );
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
                'name'   => (string) $label,
                'height' => $height,
                'revenue'=> number_format($val, 0),
            ];
        }
        return $items;
    }
}