<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important}</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"></div>

    <aside :class="[sidebarCollapsed ? 'w-20' : 'w-64', sidebarOpen ? 'translate-x-0' : '']"
        class="fixed inset-y-0 left-0 z-50 flex -translate-x-full flex-col bg-slate-900 text-slate-300 transition-all duration-300 lg:translate-x-0">

        <div class="flex items-center gap-3 border-b border-slate-800/70 px-4 py-4" :class="sidebarCollapsed ? 'justify-center' : ''">
            <img src="{{ asset('assets/logo_bulat.png') }}" alt="Bank Soal UT" class="h-10 w-10 shrink-0 rounded-full object-cover">
            <div class="min-w-0" x-show="!sidebarCollapsed" x-cloak>
                <p class="truncate text-base font-extrabold leading-tight text-white">Bank Soal UT</p>
                <p class="text-[11px] font-medium text-slate-400">Panel Admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-5">
            <p class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500" x-show="!sidebarCollapsed" x-cloak>Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}" title="Dashboard" :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                <span x-show="!sidebarCollapsed" x-cloak>Dashboard</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" title="Mata Kuliah" :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('admin.courses.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                <span x-show="!sidebarCollapsed" x-cloak>Mata Kuliah</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" title="Order" :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg>
                <span x-show="!sidebarCollapsed" x-cloak>Order</span>
            </a>
            <a href="{{ route('admin.users.index') }}" title="Pengguna" :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                <span x-show="!sidebarCollapsed" x-cloak>Pengguna</span>
            </a>
        </nav>

        <div class="border-t border-slate-800 p-4" :class="sidebarCollapsed ? 'px-2' : ''">
            <div class="flex items-center gap-3 rounded-xl bg-slate-800/60 p-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                <div class="min-w-0 flex-1" x-show="!sidebarCollapsed" x-cloak>
                    <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                    </button>
                </form>
            </div>
            <a href="{{ route('catalog.index') }}" title="Kembali ke Situs" class="mt-3 flex items-center justify-center gap-2 rounded-xl border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-400 transition-colors hover:bg-slate-800 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                <span x-show="!sidebarCollapsed" x-cloak>Kembali ke Situs</span>
            </a>
        </div>
    </aside>

    <div class="flex min-h-screen flex-col transition-all duration-300" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6">
            <div class="flex items-center gap-2">
                <button @click="sidebarOpen = true" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 lg:hidden" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 lg:block" aria-label="Perkecil sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" /></svg>
                </button>
                <h1 class="text-lg font-extrabold text-slate-900">@yield('title', 'Admin Panel')</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-slate-500 sm:block">{{ auth()->user()->name }}</span>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-700">Admin</span>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('admin_content')
        </main>
    </div>

    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.300ms
            class="fixed bottom-6 right-6 z-[70] flex max-w-sm items-start gap-3 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-400"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
            <span>{{ session('status') }}</span>
            <button type="button" @click="show = false" class="ml-auto -mr-1 rounded-lg p-1 text-slate-400 transition-colors hover:text-white" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @stack('scripts')
</body>
</html>

