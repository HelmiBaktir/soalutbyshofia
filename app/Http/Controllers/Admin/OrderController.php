<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')->withCount('items')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($search = trim((string) $request->query('search')), function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('id', 'like', "%{$search}%")
                        ->orWhere('guest_name', 'ilike', "%{$search}%")
                        ->orWhere('guest_email', 'ilike', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"));
                });
            })
            ->when($request->query('from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->query('to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->when(in_array($request->query('sort'), ['id', 'created_at', 'total_amount', 'status'], true), function ($q) use ($request) {
                $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
                $q->orderBy($request->query('sort'), $direction);
            }, fn ($q) => $q->latest())
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.course', 'entitlements.course', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Order $order, CheckoutService $checkout)
    {
        if ($order->status !== 'paid') {
            $checkout->markAsPaid($order, 'MANUAL-'.$order->id);
        }

        $email = $order->user?->email ?? $order->guest_email;
        if ($email) {
            Mail::to($email)->send(new PaymentSuccessMail($order->fresh()));
        }

        return back()->with('status', 'Order dikonfirmasi & akses diberikan.');
    }

    public function resetDownload(Order $order)
    {
        foreach ($order->entitlements as $entitlement) {
            $entitlement->update(['download_expires_at' => now()->addMonths(3)]);
        }

        return back()->with('status', 'Masa download di-reset (3 bulan ke depan).');
    }
}
