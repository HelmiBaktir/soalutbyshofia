<footer class="relative overflow-hidden bg-slate-900 text-slate-300">
    <div class="h-1 w-full animate-gradient bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-500"></div>

    <div class="pointer-events-none absolute -top-24 right-10 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl animate-blob"></div>
    <div class="pointer-events-none absolute -bottom-16 left-0 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl animate-blob" style="animation-delay: 3s;"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo_bulat.png') }}" alt="Bank Soal UT By Shofia" class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-500/40">
                    <span class="text-lg font-extrabold text-white">Bank Soal UT <span class="bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent">By Shofia</span></span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                    Bank soal &amp; latihan soal mata kuliah Universitas Terbuka, lengkap dengan pembahasan. Beli, download PDF, dan belajar kapan saja.
                </p>
                <a href="https://wa.me/6285749734405" target="_blank" rel="noopener"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    WhatsApp
                </a>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Navigasi</h4>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-400">
                    <li><a href="{{ route('catalog.index') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Katalog</a></li>
                    <li><a href="{{ route('page.contact') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Kontak</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Daftar</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Informasi</h4>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-400">
                    <li><a href="{{ route('page.about') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Tentang Kami</a></li>
                    <li><a href="{{ route('page.terms') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Syarat &amp; Ketentuan</a></li>
                    <li><a href="{{ route('page.refund') }}" class="inline-block transition-all duration-300 hover:translate-x-1 hover:text-cyan-300">Kebijakan Refund</a></li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Bantuan</h4>
                <p class="mt-4 text-sm leading-relaxed text-slate-400">
                    Ada pertanyaan seputar produk &amp; pembelian? Chat kami langsung.
                </p>
                <a href="https://wa.me/6285749734405" target="_blank" rel="noopener" class="mt-3 inline-block text-sm font-semibold text-emerald-400 transition-colors hover:text-emerald-300">
                    WhatsApp
                </a>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-slate-800 pt-6 sm:flex-row">
            <p class="text-xs text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
            <p class="text-xs text-slate-600">Layanan independen — bukan situs resmi Universitas Terbuka.</p>
        </div>
    </div>
</footer>
