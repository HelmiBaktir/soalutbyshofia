@extends('layouts.app')

@section('title', 'Kontak - '.config('app.name'))
@section('meta_description', 'Hubungi Bank Soal UT By Shofia untuk pertanyaan seputar produk, pembelian, atau akses download.')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500">
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle, #ffffff 1.5px, transparent 1.5px); background-size: 26px 26px;"></div>
    <div class="pointer-events-none absolute -left-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl animate-blob"></div>
    <div class="pointer-events-none absolute -bottom-20 right-0 h-80 w-80 rounded-full bg-cyan-200/20 blur-3xl animate-blob" style="animation-delay: 2.5s;"></div>
    <div class="pointer-events-none absolute -right-20 -top-20 h-52 w-52 rounded-full border-2 border-dashed border-white/30 animate-spin-slow"></div>

    <div class="relative mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-20">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm animate-fade-in-up">
            <span class="h-2 w-2 rounded-full bg-cyan-300 animate-pulse-soft"></span>
            Hubungi Kami
        </span>
        <h1 class="mt-5 text-4xl font-black tracking-tight text-white animate-fade-in-up delay-100 sm:text-5xl">
            Ada <span class="animate-gradient bg-gradient-to-r from-cyan-200 via-white to-cyan-200 bg-clip-text text-transparent">Pertanyaan?</span>
        </h1>
        <p class="mx-auto mt-4 max-w-xl text-lg text-blue-50 animate-fade-in-up delay-200">
            Kirim pesan atau chat kami langsung — kami siap membantu pertanyaan seputar produk, pembelian, dan akses download.
        </p>
    </div>
</section>

{{-- KONTAK --}}
<section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-5">

        {{-- Info --}}
        <div class="space-y-5 lg:col-span-2">
            <a href="https://wa.me/6285749734405" target="_blank" rel="noopener"
                class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg animate-fade-in-left">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                </span>
                <div>
                    <p class="font-bold text-slate-900">WhatsApp</p>
                    <p class="text-sm text-slate-500">Chat dengan kami</p>
                </div>
            </a>

            <a href="mailto:fikaafizh7@gmail.com"
                class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg animate-fade-in-left delay-100">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                </span>
                <div>
                    <p class="font-bold text-slate-900">Email</p>
                    <p class="text-sm text-slate-500">fikaafizh7@gmail.com</p>
                </div>
            </a>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-6 animate-fade-in-left delay-200">
                <p class="text-sm font-semibold text-blue-700">Jam Operasional</p>
                <p class="mt-2 text-sm text-blue-600">Kami usahakan membalas setiap pesan secepat mungkin.</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:col-span-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 animate-fade-in-right">
                <h2 class="text-xl font-extrabold text-slate-900">Kirim Pesan</h2>
                <p class="mt-1 text-sm text-slate-500">Isi formulir di bawah, kami akan membalas via email.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                        @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                    </div>
                @endif

                <form action="{{ route('page.contact.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Pesan</label>
                        <textarea name="message" rows="5" placeholder="Tulis pertanyaan Anda..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-lg">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
