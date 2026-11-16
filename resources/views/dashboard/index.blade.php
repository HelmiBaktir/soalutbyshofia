@extends('layouts.app')

@section('title', 'Dashboard - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Repository Saya</h1>
    <p class="mt-1 text-slate-500">Halo, {{ auth()->user()->name }}! Berikut koleksi latihan soal yang Anda miliki.</p>

    {{-- Statistik ringkas --}}
    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['courses'] }}</p>
                    <p class="text-xs text-slate-500">Mata Kuliah</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-500">Download Aktif</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['orders'] }}</p>
                    <p class="text-xs text-slate-500">Total Order</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </span>
                <div>
                    <p class="text-lg font-extrabold text-slate-900">Rp {{ number_format($stats['spent'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Total Belanja</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Mata kuliah yang dimiliki --}}
    <h2 class="mt-8 text-lg font-bold text-slate-900">Koleksi Latihan Soal</h2>
    @if ($entitlements->isEmpty())
        <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-400">
            Belum memiliki mata kuliah. <a href="{{ route('catalog.index') }}" class="font-semibold text-blue-600">Jelajahi katalog</a>
        </div>
    @else
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($entitlements as $entitlement)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <div class="bg-gradient-to-br from-blue-600 to-cyan-600 px-5 py-4 text-white">
                        <div class="flex items-center justify-between">
                            <span class="rounded-md bg-white/20 px-2 py-0.5 text-xs font-bold">{{ $entitlement->course->code }}</span>
                            @if ($entitlement->isDownloadActive())
                                <span class="rounded-full bg-emerald-400/90 px-2 py-0.5 text-xs font-semibold text-white">Aktif</span>
                            @else
                                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold text-white">Kedaluwarsa</span>
                            @endif
                        </div>
                        <h3 class="mt-2 font-bold">{{ $entitlement->course->name }}</h3>
                        <p class="mt-0.5 text-xs text-blue-100">{{ $entitlement->course->prodi }}</p>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-slate-400">
                            Download s/d {{ $entitlement->download_expires_at?->format('d M Y') ?? '-' }}
                        </p>
                        @if ($entitlement->isDownloadActive())
                            <a href="{{ $entitlement->downloadUrl() }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Riwayat pesanan --}}
    <h2 class="mt-10 text-lg font-bold text-slate-900">Riwayat Pesanan</h2>
    @if ($orders->isEmpty())
        <p class="mt-4 text-sm text-slate-400">Belum ada pesanan.</p>
    @else
        <div class="mt-4 space-y-3">
            @foreach ($orders as $order)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold text-slate-900">Order #{{ $order->id }}</p>
                            <p class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                            {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-600' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">
                        {{ $order->items->pluck('course.code')->join(', ') }}
                    </p>
                    <p class="mt-1 font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
