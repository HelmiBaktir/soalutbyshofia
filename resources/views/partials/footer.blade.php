<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-4">
            <div>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo_bulat.png') }}" alt="Bank Soal UT By Shofia" class="h-12 w-12 rounded-full object-cover">
                    <span class="font-extrabold text-slate-900">Bank Soal UT <span class="text-blue-600">By Shofia</span></span>
                </div>
                <p class="mt-3 text-sm text-slate-500">
                    Bank soal & latihan soal mata kuliah Universitas Terbuka, siap diunduh kapan saja.
                </p>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wide text-slate-900">Navigasi</h4>
                <ul class="mt-3 space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('catalog.index') }}" class="transition-colors hover:text-blue-600">Katalog</a></li>
                    <li><a href="{{ route('page.contact') }}" class="transition-colors hover:text-blue-600">Kontak</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="transition-colors hover:text-blue-600">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="transition-colors hover:text-blue-600">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="transition-colors hover:text-blue-600">Daftar</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wide text-slate-900">Informasi</h4>
                <ul class="mt-3 space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('page.about') }}" class="transition-colors hover:text-blue-600">Tentang Kami</a></li>
                    <li><a href="{{ route('page.terms') }}" class="transition-colors hover:text-blue-600">Syarat & Ketentuan</a></li>
                    <li><a href="{{ route('page.refund') }}" class="transition-colors hover:text-blue-600">Kebijakan Refund</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wide text-slate-900">Bantuan</h4>
                <p class="mt-3 text-sm text-slate-500">
                    Hubungi kami via WhatsApp untuk pertanyaan seputar produk & pembelian.
                </p>
            </div>
        </div>

        <div class="mt-8 border-t border-slate-200 pt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.
        </div>
    </div>
</footer>
