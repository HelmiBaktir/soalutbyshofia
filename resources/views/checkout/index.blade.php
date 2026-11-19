@extends('layouts.app')

@section('title', 'Checkout - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Checkout</h1>

    @if ($courses->isEmpty())
        <div class="mt-8 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">
            Belum ada mata kuliah yang dipilih.
            <a href="{{ route('catalog.index') }}" class="font-semibold text-blue-600">Pilih di katalog</a>
        </div>
    @else
        <div class="mt-6 grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('checkout.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    @csrf
                    @foreach ($courses as $course)
                        <input type="hidden" name="course_ids[]" value="{{ $course->id }}">
                    @endforeach

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @auth
                        <div class="mb-6 rounded-xl bg-blue-50 p-4 text-sm text-blue-700">
                            Login sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).
                        </div>
                    @else
                        <div class="mb-6 rounded-xl bg-amber-50 p-4 text-sm text-amber-700">
                            Anda checkout sebagai tamu. Link download akan dikirim ke email Anda setelah pembayaran.
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                            </div>
                        </div>
                    @endauth

                    <div class="mt-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                        <input type="hidden" name="payment_method" value="manual_transfer">
                        <div class="flex items-center gap-3 rounded-xl border border-blue-300 bg-blue-50/50 p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 shrink-0 text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Transfer Manual (Rekening / QRIS)</p>
                                <p class="text-xs text-slate-500">Transfer ke rekening / QRIS, lalu kirim bukti transfer untuk verifikasi.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-6 w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">
                        Buat Pesanan &amp; Lihat Instruksi Transfer
                    </button>
                </form>
            </div>

            <div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-400">Ringkasan Pesanan</h2>
                    <ul class="mt-4 divide-y divide-slate-100">
                        @foreach ($courses as $course)
                            <li class="flex items-start justify-between gap-2 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $course->code }}</p>
                                    <p class="text-xs text-slate-400">{{ $course->name }}</p>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                        <span class="font-semibold text-slate-700">Total</span>
                        <span class="text-xl font-extrabold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
