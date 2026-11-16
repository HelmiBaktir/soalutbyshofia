@extends('layouts.app')

@section('title', 'Status Pembayaran - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">

        @if (! $order)
            <p class="text-slate-500">Order tidak ditemukan.</p>
        @elseif ($order->status === 'paid')
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <h1 class="mt-4 text-2xl font-extrabold text-slate-900">Pembayaran Berhasil</h1>
            <p class="mt-2 text-slate-500">Order #{{ $order->id }} telah dibayar. Akses download sudah diberikan.</p>

            <div class="mt-6 space-y-2 text-left">
                @foreach ($order->entitlements as $entitlement)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-800">{{ $entitlement->course->code }}</p>
                            <p class="text-xs text-slate-400">{{ $entitlement->course->name }}</p>
                        </div>
                        @if ($entitlement->isDownloadActive())
                            <a href="{{ $entitlement->downloadUrl() }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition-all duration-300 hover:bg-emerald-700">Download</a>
                        @else
                            <span class="text-xs text-slate-400">Kedaluwarsa</span>
                        @endif
                    </div>
                @endforeach
            </div>

        @elseif ($order->status === 'pending')
            <h1 class="text-2xl font-extrabold text-slate-900">Menunggu Pembayaran</h1>
            <p class="mt-2 text-slate-500">
                Order #{{ $order->id }} menunggu pembayaran. Ikuti instruksi di halaman pembayaran/email.
            </p>
            <p class="mt-2 text-sm text-slate-400">Total: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>

        @elseif (in_array($order->status, ['expired', 'failed']))
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            <h1 class="mt-4 text-2xl font-extrabold text-slate-900">Pembayaran {{ ucfirst($order->status) }}</h1>
            <p class="mt-2 text-slate-500">Order #{{ $order->id }} tidak dapat diproses. Silakan lakukan order ulang.</p>
            <a href="{{ route('catalog.index') }}" class="mt-6 inline-block rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition-all duration-300 hover:bg-blue-700">Kembali ke Katalog</a>
        @endif
    </div>
</div>
@endsection
