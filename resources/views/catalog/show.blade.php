@extends('layouts.app')

@section('title', 'Soal '.$course->code.' '.$course->name.' + Pembahasan – Universitas Terbuka')
@section('meta_description', 'Soal '.$course->code.' '.$course->name.' + pembahasan lengkap – Universitas Terbuka. Beli & download PDF bank soal UT dengan pembahasan.')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

    <nav class="mb-6 text-sm text-slate-400">
        <a href="{{ route('catalog.index') }}" class="transition-colors hover:text-blue-600">Katalog</a>
        <span class="mx-1">/</span>
        <span class="text-slate-600">{{ $course->code }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-3">
        {{-- Info utama --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/logo_ut.png') }}" alt="Universitas Terbuka" class="h-16 w-16 shrink-0 object-contain">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600">{{ $course->code }}</span>
                            @if ($course->semester)
                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">Semester {{ $course->semester }}</span>
                            @endif
                            @if ($course->is_bundle)
                                <span class="rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600">Bundle</span>
                            @endif
                        </div>
                        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $course->name }}</h1>
                        <p class="mt-1 text-sm font-medium text-slate-500">Program Studi: {{ $course->prodi }}</p>
                    </div>
                </div>

                @if ($course->description)
                    <p class="mt-5 leading-relaxed text-slate-600">{{ $course->description }}</p>
                @endif

                @if ($course->jumlah_soal || $course->masa_ujian)
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @if ($course->jumlah_soal)
                            <div class="rounded-xl bg-blue-50 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">Jumlah Soal</p>
                                <p class="mt-1 text-lg font-extrabold text-blue-700">{{ number_format($course->jumlah_soal, 0, ',', '.') }} soal</p>
                            </div>
                        @endif
                        @if ($course->masa_ujian)
                            <div class="rounded-xl bg-emerald-50 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Masa Ujian</p>
                                <p class="mt-1 text-lg font-extrabold text-emerald-700">{{ $course->masa_ujian }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Card beli / download --}}
        <div>
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Harga</p>
                <p class="mt-1 text-3xl font-extrabold text-slate-900">Rp {{ number_format($course->price, 0, ',', '.') }}</p>

                @if ($entitlement)
                    @if ($entitlement->isDownloadActive())
                        <a href="{{ $downloadUrl }}"
                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download PDF
                        </a>
                        <p class="mt-2 text-center text-xs text-slate-400">Akses download s/d {{ $entitlement->download_expires_at->format('d M Y') }}</p>
                    @else
                        <div class="mt-5 rounded-xl bg-slate-100 px-4 py-3 text-center text-sm text-slate-500">
                            Akses viewer tetap tersedia, namun masa download telah berakhir.
                        </div>
                    @endif
                @else
                    <form action="{{ route('checkout.index') }}" method="GET">
                        <input type="hidden" name="course_ids" value="{{ $course->id }}">
                        <button type="submit"
                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">
                            Beli Sekarang
                        </button>
                    </form>
                    <form action="{{ route('cart.add', $course) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return addToCart(this)"
                            class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 px-4 py-3 font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </form>
                    <p class="mt-2 text-center text-xs text-slate-400">Akses download PDF selama 3 bulan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
