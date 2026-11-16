<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing Page — Hero Section</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#FAF7F2] text-slate-900 antialiased" x-data="{ open: false }">

    {{-- Navbar --}}
    <header class="flex items-center justify-between px-6 py-6 md:px-12 lg:px-16">
        {{-- Logo placeholder --}}
        <div class="flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">L</span>
            <span class="text-lg font-bold tracking-tight">LOGO</span>
        </div>

        {{-- Menu (desktop) --}}
        <nav class="hidden items-center gap-8 text-sm font-medium text-slate-700 md:flex">
            <a href="#" class="transition-colors hover:text-orange-500">Services</a>
            <a href="#" class="transition-colors hover:text-orange-500">Work</a>
            <a href="#" class="transition-colors hover:text-orange-500">Contact</a>
            <a href="#" class="transition-colors hover:text-orange-500">About</a>
        </nav>

        {{-- Login button --}}
        <div class="flex items-center gap-3">
            <button class="hidden rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-slate-700 md:block">Login</button>
            <button @click="open = !open" class="rounded-lg p-2 text-slate-700 hover:bg-slate-200 md:hidden" aria-label="Menu">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </header>

    {{-- Mobile menu --}}
    <div x-show="open" x-transition @click.away="open = false" class="flex flex-col gap-3 border-b border-slate-200 bg-white px-6 py-4 text-sm font-medium text-slate-700 md:hidden">
        <a href="#" class="transition-colors hover:text-orange-500">Services</a>
        <a href="#" class="transition-colors hover:text-orange-500">Work</a>
        <a href="#" class="transition-colors hover:text-orange-500">Contact</a>
        <a href="#" class="transition-colors hover:text-orange-500">About</a>
        <button class="mt-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">Login</button>
    </div>

    {{-- Hero --}}
    <main>
        <section class="grid items-center gap-10 px-6 py-10 md:grid-cols-2 md:px-12 lg:px-16 lg:py-16">

            {{-- Kolom kiri: teks --}}
            <div class="text-left">
                <h1 class="text-6xl font-black uppercase leading-[0.9] tracking-tight md:text-8xl">
                    Study<br>Abroad
                </h1>
                <h2 class="mt-5 text-2xl font-bold uppercase tracking-wider text-orange-500 md:text-3xl">
                    Explore the World
                </h2>
                <p class="mt-6 max-w-md text-sm leading-relaxed text-slate-600 md:text-base">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                </p>
                <button class="mt-8 rounded-lg bg-orange-500 px-8 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-orange-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600">
                    Read More
                </button>
            </div>

            {{-- Kolom kanan: ilustrasi --}}
            <div class="relative flex items-center justify-center">
                {{-- Blob abstrak di belakang ilustrasi --}}
                <div class="absolute h-72 w-72 rounded-[42%_58%_60%_40%_/_45%_45%_55%_55%] bg-orange-200/70 md:h-[28rem] md:w-[28rem]"></div>
                <img src="{{ asset('assets/assets_dasboard.png') }}" alt="Ilustrasi mahasiswa dengan ransel" class="relative z-10 max-w-full object-contain">
            </div>

        </section>
    </main>

</body>
</html>
