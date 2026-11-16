@extends('layouts.app')

@section('title', 'Kebijakan Refund - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <article class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-extrabold text-slate-900">Kebijakan Refund</h1>

        <div class="mt-6 space-y-6 text-slate-600">
            <section>
                <h2 class="font-bold text-slate-900">1. Produk Digital</h2>
                <p class="mt-2">Seluruh produk berupa file PDF digital. Karena sifatnya yang dapat langsung diunduh dan disalin, pada prinsipnya pembelian bersifat final (tidak dapat dikembalikan).</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">2. Pengajuan Refund</h2>
                <p class="mt-2">Refund dapat dipertimbangkan apabila: (a) pembayaran berhasil namun akses download tidak diberikan; (b) file PDF rusak/tidak dapat dibuka; atau (c) terjadi kesalahan duplikasi pembayaran.</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">3. Cara Pengajuan</h2>
                <p class="mt-2">Hubungi kami melalui WhatsApp/email dengan menyertakan bukti pembayaran dan alasan pengajuan. Proses verifikasi maksimal 3 hari kerja.</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">4. Persetujuan</h2>
                <p class="mt-2">Refund yang disetujui akan dikembalikan ke metode pembayaran semula sesuai kebijakan payment gateway.</p>
            </section>
        </div>
    </article>
</div>
@endsection
