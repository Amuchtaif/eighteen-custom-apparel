<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports and analytics metrics dashboard.
     */
    public function index()
    {
        // 1. Monthly Sales Performance for the Current Year
        $monthlySales = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlySales[$m] = 0;
        }

        $driverName = DB::connection()->getDriverName();
        if ($driverName === 'sqlite') {
            $salesQuery = Payment::selectRaw('cast(strftime("%m", payment_date) as integer) as month, SUM(amount) as total')
                ->whereYear('payment_date', now()->year)
                ->groupBy('month')
                ->get();
        } else {
            $salesQuery = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
                ->whereYear('payment_date', now()->year)
                ->groupBy('month')
                ->get();
        }

        foreach ($salesQuery as $sq) {
            $monthlySales[(int) $sq->month] = (float) $sq->total;
        }

        // Convert month numbers to name labels
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $salesLabels = [];
        $salesValues = [];
        foreach ($monthlySales as $m => $val) {
            $salesLabels[] = $monthNames[$m];
            $salesValues[] = $val;
        }

        // 2. Attendance Recap for Current Month
        $attendanceRecap = Employee::with(['user', 'position', 'department'])
            ->withCount(['attendances' => function ($q) {
                $q->whereMonth('check_in_time', now()->month)
                  ->whereYear('check_in_time', now()->year);
            }])
            ->orderBy('employee_code')
            ->get();

        return view('reports.index', compact('salesLabels', 'salesValues', 'attendanceRecap'));
    }
}
