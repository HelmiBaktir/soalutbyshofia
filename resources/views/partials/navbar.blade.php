@php($cartCount = count(array_values(array_filter(array_map('intval', (array) session('cart', []))))))
<header x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 40"
    class="sticky top-0 z-50 bg-white/85 backdrop-blur-md transition-all duration-500"
    :class="scrolled ? 'mx-4 mt-3 rounded-full border border-slate-200/70 shadow-lg shadow-slate-900/5 sm:mx-12 lg:mx-28' : 'border-b border-slate-200/70'">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('assets/logo_bulat.png') }}" alt="Bank Soal UT By Shofia" class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-100">
                <span class="font-extrabold text-slate-900">Bank Soal UT <span class="text-blue-600">By Shofia</span></span>
            </a>

            {{-- Search (desktop) --}}
            <form action="{{ route('catalog.index') }}" method="GET" class="hidden flex-1 max-w-md md:block">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari mata kuliah (kode / nama / prodi)..."
                        class="w-full rounded-full border border-slate-200 bg-slate-50 py-2 pl-10 pr-4 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </div>
            </form>

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-1 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition-all duration-300 hover:bg-slate-100 hover:text-slate-900">Dashboard</a>
                @endauth
                <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition-all duration-300 hover:bg-slate-100 hover:text-slate-900">Katalog</a>
                <a href="{{ route('page.contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition-all duration-300 hover:bg-slate-100 hover:text-slate-900">Kontak</a>
                <a href="{{ route('page.about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition-all duration-300 hover:bg-slate-100 hover:text-slate-900">Tentang</a>

                @auth
                    <a href="{{ route('cart.index') }}" id="cart-icon" class="relative rounded-lg p-2 text-slate-600 transition-all duration-300 hover:bg-slate-100 hover:text-slate-900" title="Keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <span class="mx-3 h-6 w-0.5 rounded bg-slate-300"></span>
                    <a href="{{ route('dashboard') }}" class="hidden text-sm font-medium text-slate-600 transition-colors duration-300 hover:text-blue-600 lg:block">
                        Selamat Datang, {{ auth()->user()->name }}
                    </a>
                    {{-- Akun dropdown --}}
                    <div class="relative" x-data="{ accountOpen: false }">
                        <button @click="accountOpen = !accountOpen" class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white ring-2 ring-blue-100 transition-all duration-300 hover:bg-blue-700" title="Akun">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <div x-show="accountOpen" x-transition.opacity.duration.150ms @click.away="accountOpen = false"
                            class="absolute right-0 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('cart.index') }}" class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                                <span class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                    Keranjang
                                </span>
                                @if ($cartCount > 0)
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-600">{{ $cartCount }}</span>
                                @endif
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-blue-600 transition-colors hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Admin Panel
                                </a>
                            @endif

                            <div class="my-1 border-t border-slate-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">Masuk / Daftar</a>
                @endauth
            </nav>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="rounded-lg p-2 text-slate-600 transition-colors hover:bg-slate-100 md:hidden" aria-label="Menu">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition.opacity.duration.300ms @click.away="open = false" class="border-t border-slate-200 bg-white px-4 pb-4 md:hidden">
        <form action="{{ route('catalog.index') }}" method="GET" class="pt-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari mata kuliah..."
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-blue-400">
        </form>
        <nav class="mt-3 flex flex-col gap-1">
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Dashboard</a>
            @endauth
            <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Katalog</a>
            <a href="{{ route('page.contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Kontak</a>
            <a href="{{ route('page.about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Tentang</a>
            @auth
                <a href="{{ route('cart.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Keranjang{{ $cartCount > 0 ? ' ('.$cartCount.')' : '' }}</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-600 hover:bg-slate-100">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mt-1 rounded-full bg-blue-600 px-5 py-2.5 text-center text-sm font-bold text-white">Masuk / Daftar</a>
            @endauth
        </nav>
    </div>
</header>
