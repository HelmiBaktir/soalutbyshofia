@extends('admin.layout')

@section('title', 'Order #'.$order->id)

@section('admin_content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-slate-900">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">← Kembali</a>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-bold text-slate-900">Informasi Order</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Pembeli</dt><dd class="font-semibold text-slate-800">{{ $order->user?->name ?? $order->guest_name ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Email</dt><dd class="font-semibold text-slate-800">{{ $order->user?->email ?? $order->guest_email ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Status</dt><dd class="font-semibold {{ $order->status === 'paid' ? 'text-emerald-600' : 'text-slate-800' }}">{{ ucfirst($order->status) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Metode</dt><dd class="font-semibold text-slate-800">{{ str_replace('_', ' ', $order->payment_method ?? '-') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Total</dt><dd class="font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</dd></div>
                @if ($order->paid_at)
                    <div class="flex justify-between"><dt class="text-slate-500">Dibayar</dt><dd class="font-semibold text-slate-800">{{ $order->paid_at->format('d M Y, H:i') }}</dd></div>
                @endif
            </dl>

            @if ($order->status !== 'paid')
                <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" class="mt-5">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 font-semibold text-white transition-all duration-300 hover:bg-emerald-700">Konfirmasi Manual (Berikan Akses)</button>
                </form>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Item & Akses Download</h2>
                <form method="POST" action="{{ route('admin.orders.reset-download', $order) }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50">Reset Download (+3 bulan)</button>
                </form>
            </div>
            <ul class="mt-4 divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <li class="py-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $item->course->code }} — {{ $item->course->name }}</p>
                                <p class="text-xs text-slate-400">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            @php($ent = $order->entitlements->firstWhere('course_id', $item->course_id))
                            @if ($ent)
                                <span class="text-xs font-semibold {{ $ent->isDownloadActive() ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $ent->isDownloadActive() ? 'Aktif s/d '.$ent->download_expires_at->format('d M Y') : 'Kedaluwarsa' }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Belum ada akses</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
