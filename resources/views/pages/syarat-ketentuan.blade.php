@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <article class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-extrabold text-slate-900">Syarat & Ketentuan</h1>
        <p class="mt-2 text-sm text-slate-400">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="mt-6 space-y-6 text-slate-600">
            <section>
                <h2 class="font-bold text-slate-900">1. Layanan</h2>
                <p class="mt-2">Kami menyediakan bank soal / latihan soal mata kuliah Universitas Terbuka dalam bentuk PDF. Materi hanya untuk keperluan belajar pribadi.</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">2. Hak Akses</h2>
                <p class="mt-2">Setelah pembayaran terverifikasi, pembeli mendapat akses download PDF selama 3 bulan. Satu akun maksimal membeli 8 mata kuliah.</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">3. Penggunaan Materi</h2>
                <p class="mt-2">Materi dilarang diperjualbelikan ulang, disebarluaskan, atau digunakan di luar keperluan pribadi tanpa izin tertulis.</p>
            </section>
            <section>
                <h2 class="font-bold text-slate-900">4. Perubahan</h2>
                <p class="mt-2">Kami berhak mengubah syarat & ketentuan sewaktu-waktu. Perubahan berlaku setelah diumumkan pada halaman ini.</p>
            </section>
        </div>
    </article>
</div>
@endsection
