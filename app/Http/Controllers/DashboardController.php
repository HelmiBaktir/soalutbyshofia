<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()->with('items.course')->latest()->get();
        $entitlements = $user->entitlements()->with('course')->latest()->get();

        $stats = [
            'courses' => $entitlements->count(),
            'active' => $entitlements->filter(fn ($e) => $e->isDownloadActive())->count(),
            'orders' => $orders->count(),
            'spent' => $orders->where('status', 'paid')->sum('total_amount'),
        ];

        return view('dashboard.index', compact('orders', 'entitlements', 'stats'));
    }
}
