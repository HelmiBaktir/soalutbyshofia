@extends('layouts.app')

@section('title', 'Bank Soal UT & Pembahasan Lengkap | Soal UT By Shofia')
@section('meta_description', 'Kumpulan bank soal & latihan soal Universitas Terbuka lengkap dengan pembahasan. Beli, download PDF, dan belajar kapan saja.')

@section('content')

<section class="relative flex min-h-screen items-center overflow-hidden">
    {{-- Background + pola titik --}}
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-cyan-50"></div>
    <div class="absolute inset-0 opacity-60" style="background-image: radial-gradient(circle, #bfdbfe 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>

    {{-- Blob lembut --}}
    <div class="pointer-events-none absolute -left-10 -top-10 h-44 w-44 rounded-full bg-blue-100/60 blur-2xl animate-float"></div>
    <div class="pointer-events-none absolute right-10 top-1/4 h-64 w-64 rounded-full bg-cyan-100/50 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-8 left-1/2 h-52 w-52 rounded-full bg-blue-50/80 blur-3xl"></div>

    {{-- Titik-titik melayang --}}
    <div class="pointer-events-none absolute left-[15%] top-24 h-3 w-3 rounded-full bg-blue-400/70 animate-float"></div>
    <div class="pointer-events-none absolute right-[20%] bottom-32 h-4 w-4 rounded-full bg-cyan-400/60 animate-float" style="animation-delay: 1s;"></div>
    <div class="pointer-events-none absolute left-[8%] bottom-1/3 h-2 w-2 rounded-full bg-blue-300/70 animate-float" style="animation-delay: 2s;"></div>
    <div class="pointer-events-none absolute right-[12%] top-16 h-2.5 w-2.5 rounded-full bg-blue-500/50 animate-float" style="animation-delay: 1.5s;"></div>

    {{-- Cincin berputar --}}
    <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full border-2 border-dashed border-blue-200/70 animate-spin-slow"></div>
    <div class="pointer-events-none absolute -left-12 bottom-0 h-40 w-40 rounded-full border border-blue-100 animate-spin-slow" style="animation-direction: reverse;"></div>

    {{-- Hiasan plus --}}
    <svg class="pointer-events-none absolute left-24 top-1/3 h-5 w-5 text-blue-300 animate-float" style="animation-delay: 0.5s;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" d="M12 5v14m-7-7h14" /></svg>
    <svg class="pointer-events-none absolute right-32 bottom-1/4 h-4 w-4 text-cyan-300 animate-float" style="animation-delay: 1.8s;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" d="M12 5v14m-7-7h14" /></svg>

    <div class="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2">

            {{-- Kolom kiri: teks --}}
            <div class="text-left">
                <h1 class="text-5xl font-black uppercase leading-[0.95] tracking-tight text-slate-900 animate-fade-in-up sm:text-6xl lg:text-7xl">
                    Bank Soal<br>UT
                </h1>
                <h2 class="mt-4 text-2xl font-bold uppercase tracking-wider text-blue-600 animate-fade-in-up delay-100 sm:text-3xl">
                    By Shofia
                </h2>
                <p class="mt-6 max-w-md text-base leading-relaxed text-slate-600 animate-fade-in-up delay-200 sm:text-lg">
                    Kumpulan bank soal & latihan mata kuliah Universitas Terbuka. Beli, download PDF-nya, dan belajar kapan saja.
                </p>
                <a href="#katalog" class="mt-8 inline-block rounded-xl bg-blue-600 px-8 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-blue-600/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 animate-fade-in-up delay-300">
                    Jelajahi Sekarang
                </a>
            </div>

            {{-- Kolom kanan: ilustrasi --}}
            <div class="relative flex items-center justify-center">
                <div class="absolute h-72 w-72 rounded-[42%_58%_60%_40%_/_45%_45%_55%_55%] bg-blue-200/60 animate-float md:h-[28rem] md:w-[28rem]"></div>
                <img src="{{ asset('assets/assets_dasboard.png') }}" alt="Ilustrasi mahasiswa dengan ransel" class="relative z-10 max-w-full object-contain animate-fade-in-up delay-150">
            </div>

        </div>
    </div>

    <a href="#katalog" class="absolute bottom-6 left-1/2 -translate-x-1/2 animate-bounce text-slate-400 transition-colors hover:text-blue-600" aria-label="Scroll ke bawah">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </a>
</section>

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" id="katalog"
     x-data="{ q: '', prodi: '', semester: '',
        show(name, code, prod, sem) {
            const text = (name + ' ' + code + ' ' + prod).toLowerCase();
            return (!this.q || text.includes(this.q.toLowerCase()))
                && (!this.prodi || prod === this.prodi)
                && (!this.semester || sem === this.semester);
        } }">

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Daftar Mata Kuliah</h1>
        <p class="mt-1 text-slate-500">Kumpulan bank soal UT pilihan. Pilih, beli, dan unduh PDF-nya.</p>
    </div>

    {{-- Filter --}}
    <div class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="relative sm:col-span-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input x-model="q" type="search" placeholder="Cari real-time (kode / nama / prodi)..."
                class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
        </div>

        <select x-model="prodi" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="">Semua Prodi</option>
            @foreach ($prodis as $p)
                <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
        </select>

        <select x-model="semester" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="">Semua Semester</option>
            @foreach ($semesters as $s)
                <option value="{{ $s }}">Semester {{ $s }}</option>
            @endforeach
        </select>
    </div>

    {{-- Grid --}}
    @php
        $gradients = [
            'from-blue-500 to-blue-500',
            'from-cyan-500 to-pink-500',
            'from-emerald-500 to-teal-500',
            'from-amber-500 to-orange-500',
            'from-rose-500 to-red-500',
            'from-cyan-500 to-sky-500',
            'from-violet-500 to-cyan-500',
        ];
    @endphp
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($courses as $course)
            @php($gradient = $gradients[crc32($course->code) % count($gradients)])
            <article x-show="show('{{ addslashes($course->name) }}', '{{ $course->code }}', '{{ $course->prodi }}', '{{ $course->semester }}')"
                class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">

                {{-- Cover --}}
                <a href="{{ route('catalog.show', $course) }}" class="relative block h-44 overflow-hidden bg-white">
                    <div class="flex h-full w-full items-center justify-center">
                        <img src="{{ asset('assets/logo_ut.png') }}" alt="Universitas Terbuka" class="h-24 w-24 object-contain transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <span class="absolute left-3 top-3 rounded-md bg-black/50 px-2 py-0.5 text-xs font-bold text-white">{{ $course->code }}</span>
                    @if ($course->is_bundle)
                        <span class="absolute right-3 top-3 rounded-full bg-amber-400 px-2 py-0.5 text-[11px] font-bold text-amber-900">Bundle</span>
                    @endif
                </a>

                {{-- Body --}}
                <div class="flex flex-1 flex-col p-4">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-semibold text-blue-600">{{ $course->prodi }}</span>
                        @if ($course->semester)
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-500">Sem {{ $course->semester }}</span>
                        @endif
                    </div>

                    <a href="{{ route('catalog.show', $course) }}" class="mt-2 block flex-1">
                        <h3 class="font-bold leading-snug text-slate-900 line-clamp-2 transition-colors duration-300 group-hover:text-blue-600">{{ $course->name }}</h3>
                    </a>

                    <p class="mt-2 text-base font-extrabold text-slate-900">Rp {{ number_format($course->price, 0, ',', '.') }}</p>

                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('checkout.index', ['course_ids' => $course->id]) }}"
                            class="flex flex-1 items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700">
                            Beli Sekarang
                        </a>
                        <form action="{{ route('cart.add', $course) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return addToCart(this)" title="Tambah ke Keranjang"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition-all duration-300 hover:bg-blue-50 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">
                Tidak ada mata kuliah ditemukan.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</div>
@endsection
