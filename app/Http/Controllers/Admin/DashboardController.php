<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DownloadLog;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue' => (int) Order::where('status', 'paid')->sum('total_amount'),
            'orders' => Order::count(),
            'paid_orders' => Order::where('status', 'paid')->count(),
            'courses' => Course::count(),
            'users' => User::where('role', 'user')->count(),
            'downloads' => DownloadLog::count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $topCourses = Course::withCount(['orderItems as sales' => function ($q) {
            $q->whereHas('order', fn ($w) => $w->where('status', 'paid'));
        }])
            ->orderByDesc('sales')
            ->take(5)
            ->get();

        // Pendapatan 14 hari terakhir (untuk grafik)
        $revenueByDay = Order::where('status', 'paid')
            ->where('paid_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(paid_at) as day, SUM(total_amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $revenueLabels = [];
        $revenueData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->toDateString();
            $revenueLabels[] = $date->format('d M');
            $revenueData[] = (int) ($revenueByDay[$key] ?? 0);
        }

        // Distribusi status order (untuk grafik donut)
        $orderStatus = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topCourses',
            'revenueLabels',
            'revenueData',
            'orderStatus'
        ));
    }
}

