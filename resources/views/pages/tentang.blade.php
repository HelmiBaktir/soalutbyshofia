@extends('layouts.app')

@section('title', 'Tentang Kami - '.config('app.name'))
@section('meta_description', 'Bank Soal UT By Shofia menyediakan kisi-kisi & latihan soal beserta pembahasan untuk mahasiswa Universitas Terbuka (UT). Satu PDF per mata kuliah, lengkap kunci jawaban & alasannya.')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500">
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle, #ffffff 1.5px, transparent 1.5px); background-size: 26px 26px;"></div>
    <div class="pointer-events-none absolute -left-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl animate-blob"></div>
    <div class="pointer-events-none absolute -bottom-20 right-0 h-80 w-80 rounded-full bg-cyan-200/20 blur-3xl animate-blob" style="animation-delay: 2.5s;"></div>
    <div class="pointer-events-none absolute -right-20 -top-20 h-52 w-52 rounded-full border-2 border-dashed border-white/30 animate-spin-slow"></div>
    <div class="pointer-events-none absolute left-[12%] top-24 h-3 w-3 rounded-full bg-white/60 animate-float"></div>
    <div class="pointer-events-none absolute left-[45%] bottom-16 h-4 w-4 rounded-full bg-cyan-200/60 animate-float" style="animation-delay: 1s;"></div>
    <div class="pointer-events-none absolute right-[16%] top-12 h-2.5 w-2.5 rounded-full bg-white/50 animate-float" style="animation-delay: 1.6s;"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
        <div class="text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-up">
                <span class="h-2 w-2 rounded-full bg-cyan-300 animate-pulse-soft"></span>
                Bank Soal UT By Shofia
            </span>
            <h1 class="mt-5 text-4xl font-black leading-[1.05] tracking-tight animate-fade-in-up delay-100 sm:text-5xl lg:text-6xl">
                Tentang
                <span class="animate-gradient block bg-gradient-to-r from-cyan-200 via-white to-cyan-200 bg-clip-text text-transparent">Kami</span>
            </h1>
            <p class="mt-5 max-w-lg text-lg leading-relaxed text-blue-50 animate-fade-in-up delay-200">
                Kisi-kisi &amp; latihan soal UT beserta pembahasan, dirangkum rapi dalam satu PDF per mata kuliah.
            </p>
            <div class="mt-8 flex flex-wrap gap-3 animate-fade-in-up delay-300">
                <a href="{{ route('catalog.index') }}" class="rounded-xl bg-white px-6 py-3 text-sm font-bold text-blue-700 shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl">Jelajahi Katalog</a>
                <a href="https://wa.me/6285749734405" target="_blank" rel="noopener" class="rounded-xl border border-white/40 px-6 py-3 text-sm font-bold text-white transition-all duration-300 hover:bg-white/10">Chat WhatsApp</a>
            </div>
        </div>

        <div class="relative flex items-center justify-center animate-fade-in-right delay-200">
            <div class="absolute h-72 w-72 rounded-full bg-white/20 blur-2xl animate-pulse-soft"></div>
            <img src="{{ asset('assets/assets_aboutus.png') }}" alt="Ilustrasi belajar soal UT" class="relative z-10 max-h-80 w-auto object-contain animate-float drop-shadow-2xl">
            <div class="absolute left-0 top-8 z-20 rounded-2xl bg-white px-4 py-2.5 shadow-xl animate-float" style="animation-delay: 0.8s;">
                <p class="text-xs font-bold text-slate-800">📄 1 PDF / Mata Kuliah</p>
            </div>
            <div class="absolute -right-2 bottom-10 z-20 rounded-2xl bg-white px-4 py-2.5 shadow-xl animate-float" style="animation-delay: 1.4s;">
                <p class="text-xs font-bold text-slate-800">✅ Kunci Jawaban + Alasan</p>
            </div>
        </div>
    </div>
</section>

{{-- TENTANG KAMI --}}
<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-5 lg:items-center">
        <div class="lg:col-span-3">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Tentang Kami</span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Belajar UT Lebih Mudah &amp; Terarah</h2>
            <div class="mt-6 space-y-4 leading-relaxed text-slate-600">
                <p>
                    <strong class="text-slate-900">Bank Soal UT By Shofia</strong> menyediakan kisi-kisi dan latihan soal beserta pembahasan untuk mahasiswa Universitas Terbuka (UT). Setiap mata kuliah dirangkum dalam <strong class="text-slate-900">satu file PDF</strong> yang rapi, lengkap dengan kunci jawaban dan alasannya, serta rutin diperbarui.
                </p>
                <p>
                    Materi kami paling cocok untuk persiapan <strong class="text-slate-900">Ujian Online (UO)</strong>, tapi tetap bisa dipakai untuk belajar menghadapi <strong class="text-slate-900">Ujian Tatap Muka (UTM)</strong>.
                </p>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-cyan-500 p-1 shadow-xl">
                <div class="rounded-[22px] bg-white/95 p-8 text-center">
                    <img src="{{ asset('assets/logo_ut.png') }}" alt="Universitas Terbuka" class="mx-auto h-20 w-20 object-contain animate-float">
                    <p class="mt-4 text-sm font-semibold text-slate-500">Untuk Mahasiswa</p>
                    <p class="mt-1 text-lg font-extrabold text-slate-900">Universitas Terbuka</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KENAPA MEMILIH KAMI --}}
<section class="relative overflow-hidden bg-slate-50 py-16">
    <div class="pointer-events-none absolute inset-0 opacity-60" style="background-image: radial-gradient(circle, #bfdbfe 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Keunggulan</span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Kenapa Memilih Kami?</h2>
            <p class="mx-auto mt-3 max-w-2xl text-slate-500">Semua yang kamu butuhkan untuk persiapan ujian, dalam satu tempat.</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-blue-200 hover:shadow-xl animate-scale-in">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </div>
                <h3 class="mt-4 font-bold text-slate-900">Satu PDF per Mata Kuliah</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">Praktis dan mudah dipelajari.</p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl animate-scale-in delay-100">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3 class="mt-4 font-bold text-slate-900">Kunci Jawaban + Alasan</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">Ada kunci jawaban beserta alasannya.</p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-amber-200 hover:shadow-xl animate-scale-in delay-200">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                </div>
                <h3 class="mt-4 font-bold text-slate-900">Selalu Update</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">File rutin diperbarui &amp; jawaban keliru diperbaiki.</p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-violet-200 hover:shadow-xl animate-scale-in delay-300">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-violet-600 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
                </div>
                <h3 class="mt-4 font-bold text-slate-900">Akses Instan</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">Langsung dari dashboard setelah pembayaran dikonfirmasi.</p>
            </div>
        </div>
    </div>
</section>

{{-- HUBUNGI KAMI --}}
<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-xl">
        <div class="grid items-center gap-6 p-8 sm:p-10 lg:grid-cols-2">
            <div class="text-white">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-100">Hubungi Kami</span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Ada Pertanyaan?</h2>
                <p class="mt-3 text-emerald-50">Chat kami langsung via WhatsApp, siap membantu pertanyaan seputar produk &amp; pembelian.</p>
            </div>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-end">
                <a href="https://wa.me/6285749734405" target="_blank" rel="noopener"
                    class="flex items-center gap-3 rounded-xl bg-white px-6 py-3.5 font-bold text-emerald-700 shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

{{-- DISCLAIMER --}}
<section class="mx-auto max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm leading-relaxed text-amber-800">
        <p class="font-semibold">Catatan:</p>
        <p class="mt-1">
            Bank Soal UT By Shofia adalah layanan independen dan bukan situs resmi Universitas Terbuka. Materi ini adalah bahan latihan belajar dan tidak menjamin soal yang sama akan keluar di ujian.
        </p>
    </div>
</section>
@endsection
