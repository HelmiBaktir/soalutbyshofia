@extends('layouts.app')

@section('title', 'Keranjang - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Keranjang</h1>

    @if (session('status'))
        <div class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @auth
        <div class="mt-4 flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div>
                <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
            </div>
            <span class="ml-auto rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">Pembeli</span>
        </div>
    @endauth

    @if ($courses->isEmpty())
        <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">
            Keranjang masih kosong.
            <div class="mt-4">
                <a href="{{ route('catalog.index') }}" class="inline-block rounded-xl bg-blue-600 px-6 py-2.5 font-semibold text-white transition-all duration-300 hover:bg-blue-700">Lihat Katalog</a>
            </div>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach ($courses as $course)
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:shadow-md">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white font-extrabold">
                        {{ substr($course->code, 0, 4) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-blue-600">{{ $course->code }}</p>
                        <h3 class="font-semibold text-slate-900">{{ $course->name }}</h3>
                        <p class="text-sm text-slate-500">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                    </div>
                    <form method="POST" action="{{ route('cart.remove', $course) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg p-2 text-red-500 transition-colors hover:bg-red-50" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-slate-700">Total</span>
                <span class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="mt-4 flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">
                Lanjut ke Checkout
            </a>
        </div>
    @endif

    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-bold text-slate-900">Cara Memesan</p>
        <ol class="mt-2 space-y-1.5 text-sm text-slate-600">
            <li>1. Pilih mata kuliah di katalog, lalu klik tombol keranjang.</li>
            <li>2. Periksa kembali daftar pesanan di keranjang ini.</li>
            <li>3. Klik "Lanjut ke Checkout" untuk memilih metode pembayaran.</li>
            <li>4. Setelah pembayaran terverifikasi, akses download aktif selama 3 bulan.</li>
        </ol>
    </div>

    <div class="mt-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
        <p class="font-semibold">Apa yang terjadi saat klik "Lanjut ke Checkout"?</p>
        <p class="mt-1">
            Anda akan diarahkan ke halaman checkout untuk menyelesaikan pembayaran
            secara online melalui <strong>FinPay</strong>.
            Setelah pembayaran dikonfirmasi, akses download PDF langsung aktif.
        </p>
    </div>
</div>
@endsection
