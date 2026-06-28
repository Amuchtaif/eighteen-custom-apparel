<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main admin/staff dashboard metrics and layouts.
     */
    public function index()
    {
        $today = now()->toDateString();

        // 1. Total Active Orders
        $activeOrdersCount = Order::where('status_produksi', '!=', 'Selesai')->count();

        // 2. Total Completed Orders
        $completedOrdersCount = Order::where('status_produksi', 'Selesai')->count();

        // 3. Total Omset (Sum of total_tagihan)
        $totalOmset = Order::sum('total_tagihan');

        // 4. Total Piutang (Sum of sisa_tagihan)
        $totalPiutang = Order::sum('sisa_tagihan');

        // 5. Employee Attendance (Checked in today)
        $employeeAttendance = Attendance::whereDate('check_in_time', $today)->count();

        // 6. Production Status breakdown counts
        $productionStatusCounts = [
            'Antrian' => Order::where('status_produksi', 'Antrian')->count(),
            'Print' => Order::where('status_produksi', 'Print')->count(),
            'Press' => Order::where('status_produksi', 'Press')->count(),
            'Pola' => Order::where('status_produksi', 'Pola')->count(),
            'Jahit' => Order::where('status_produksi', 'Jahit')->count(),
            'Finish' => Order::where('status_produksi', 'Finish')->count(),
            'Siap Kirim' => Order::where('status_produksi', 'Siap Kirim')->count(),
            'Selesai' => Order::where('status_produksi', 'Selesai')->count(),
        ];

        // 7. Closest Deadlines (Max 10 active orders)
        $closestDeadlines = Order::with('customer')
            ->where('status_produksi', '!=', 'Selesai')
            ->orderBy('deadline', 'asc')
            ->limit(10)
            ->get();

        // 8. Recent Orders (For general list)
        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'activeOrdersCount',
            'completedOrdersCount',
            'totalOmset',
            'totalPiutang',
            'employeeAttendance',
            'productionStatusCounts',
            'closestDeadlines',
            'recentOrders'
        ));
    }
}
