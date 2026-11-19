@extends('layouts.app')

@section('title', 'Instruksi Pembayaran - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">

    <div class="text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-amber-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Menunggu Pembayaran</h1>
        <p class="mt-2 text-slate-500">Selesaikan transfer sesuai instruksi berikut, lalu kirim bukti transfer untuk verifikasi.</p>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-bold text-slate-900">Order #{{ $order->id }}</h2>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu Pembayaran</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Total yang harus dibayar:</p>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

        <ul class="mt-4 divide-y divide-slate-100 text-sm">
            @foreach ($order->items as $item)
                <li class="flex justify-between gap-2 py-2">
                    <span class="text-slate-600">{{ $item->course->code }} &mdash; {{ $item->course->name }}</span>
                    <span class="shrink-0 font-semibold text-slate-800">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-900">Transfer ke Rekening</h2>
        <div class="mt-4 rounded-xl bg-slate-50 p-5">
            <p class="text-sm text-slate-500">Nama Bank</p>
            <p class="text-xl font-extrabold text-slate-900">{{ config('manual_payment.bank_name') }}</p>

            <p class="mt-4 text-sm text-slate-500">Nomor Rekening</p>
            <p class="text-2xl font-extrabold tracking-widest text-blue-600">{{ config('manual_payment.account_number') ?: 'Segera diisi' }}</p>

            <p class="mt-4 text-sm text-slate-500">Atas Nama</p>
            <p class="text-lg font-bold text-slate-900">{{ config('manual_payment.account_holder') }}</p>
        </div>

        @if (config('manual_payment.qris_image'))
            <div class="mt-5 text-center">
                <p class="text-sm font-semibold text-slate-700">atau Scan QRIS</p>
                <img src="{{ asset(config('manual_payment.qris_image')) }}" alt="QRIS" class="mx-auto mt-3 h-56 w-56 object-contain">
            </div>
        @endif
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-900">Langkah Selanjutnya</h2>
        <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-600">
            <li>Lakukan transfer sesuai nominal <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>.</li>
            <li>Kirim bukti transfer ke WhatsApp kami (0857-4973-4405).</li>
            <li>Tim kami akan memverifikasi dan mengaktifkan akses download Anda (link dikirim via email).</li>
        </ol>

        <a href="https://wa.me/6285749734405?text={{ urlencode('Halo, saya sudah transfer untuk Order #'.$order->id.' sebesar Rp '.number_format($order->total_amount, 0, ',', '.')) }}"
            target="_blank" rel="noopener"
            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            Kirim Bukti Transfer via WhatsApp
        </a>
    </div>
</div>
@endsection
