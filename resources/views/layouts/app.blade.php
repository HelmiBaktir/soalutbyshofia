<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo_bulat.png') }}">

    @if (config('app.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('app.google_site_verification') }}">
    @endif

    <title>@yield('title', 'Bank Soal UT & Pembahasan Lengkap | Soal UT By Shofia')</title>
    <meta name="description" content="@yield('meta_description', 'Bank soal & latihan soal Universitas Terbuka lengkap dengan pembahasan. Beli, download PDF, dan belajar kapan saja.')">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Soal UT By Shofia">
    <meta property="og:title" content="@yield('title', 'Bank Soal UT & Pembahasan Lengkap | Soal UT By Shofia')">
    <meta property="og:description" content="@yield('meta_description', 'Bank soal & latihan soal Universitas Terbuka lengkap dengan pembahasan. Beli, download PDF, dan belajar kapan saja.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script>
        function addToCart(btn) {
            const cart = document.getElementById('cart-icon');
            const form = btn.closest('form');
            if (!cart || !form) return true;

            const btnRect = btn.getBoundingClientRect();
            const cartRect = cart.getBoundingClientRect();
            const sx = btnRect.left + btnRect.width / 2;
            const sy = btnRect.top + btnRect.height / 2;
            const ex = cartRect.left + cartRect.width / 2;
            const ey = cartRect.top + cartRect.height / 2;

            // Titik utama
            const dot = document.createElement('span');
            dot.style.cssText = 'position:fixed;z-index:9999;width:24px;height:24px;border-radius:9999px;background:#2563eb;box-shadow:0 0 0 6px rgba(37,99,235,0.25);pointer-events:none;transition:all 1.2s cubic-bezier(0.35,0,0.65,1);';
            dot.style.left = (sx - 12) + 'px';
            dot.style.top = (sy - 12) + 'px';
            document.body.appendChild(dot);

            // Ekor komet (titik-titik mengecil di belakang)
            const trail = [];
            for (let i = 1; i <= 4; i++) {
                const t = document.createElement('span');
                const size = 24 - i * 3;
                t.style.cssText = 'position:fixed;z-index:9998;width:' + size + 'px;height:' + size + 'px;border-radius:9999px;background:#93c5fd;opacity:' + (0.75 - i * 0.15) + ';pointer-events:none;transition:all 1.2s cubic-bezier(0.35,0,0.65,1) ' + (i * 0.1) + 's;';
                t.style.left = (sx - size / 2) + 'px';
                t.style.top = (sy - size / 2) + 'px';
                document.body.appendChild(t);
                trail.push(t);
            }

            requestAnimationFrame(() => {
                dot.style.left = (ex - 12) + 'px';
                dot.style.top = (ey - 12) + 'px';
                dot.style.transform = 'scale(0.4)';
                dot.style.opacity = '0.85';

                trail.forEach((t, i) => {
                    const size = 24 - (i + 1) * 3;
                    t.style.left = (ex - size / 2) + 'px';
                    t.style.top = (ey - size / 2) + 'px';
                    t.style.transform = 'scale(0.2)';
                    t.style.opacity = '0';
                });
            });

            setTimeout(() => {
                dot.remove();
                trail.forEach(t => t.remove());
                cart.classList.remove('cart-shake');
                void cart.offsetWidth;
                cart.classList.add('cart-shake');
            }, 1250);

            // Tambah ke keranjang via AJAX (tanpa refresh halaman)
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            })
            .then(resp => {
                if (resp.status === 401) {
                    return resp.json().then(d => { window.location.href = (d && d.redirect) || '/login'; return null; });
                }
                return resp.json();
            })
            .then(data => {
                if (data && typeof data.count === 'number') {
                    updateCartBadge(data.count);
                    showCartToast(data.message || 'Ditambahkan ke keranjang');
                }
            })
            .catch(() => {});

            return false;
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count');
            if (!badge) return;
            badge.textContent = count;
            badge.classList.toggle('hidden', count <= 0);
        }

        function showCartToast(message) {
            const toast = document.getElementById('cart-toast');
            if (!toast) return;
            toast.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-1');
            toast.classList.add('opacity-100', 'translate-y-0');
            clearTimeout(window.__cartToastTimer);
            window.__cartToastTimer = setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-1');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased flex flex-col">

    @include('partials.navbar')

    <main class="flex-1 animate-fade-in-up">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.whatsapp')

    {{-- Toast notifikasi --}}
    @if (session('status'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed right-4 top-24 z-50 flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-emerald-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('status') }}
        </div>
    @endif

    @stack('scripts')
</body>
</html>
