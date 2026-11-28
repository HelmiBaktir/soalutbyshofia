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

{{ $courses->links('pagination.catalog') }}
