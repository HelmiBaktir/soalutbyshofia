@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="mt-10 flex justify-center">
        <div class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white/90 p-1.5 shadow-lg shadow-slate-900/10 backdrop-blur animate-fade-in-up">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="pointer-events-none flex h-9 w-9 items-center justify-center rounded-full text-slate-300" aria-disabled="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-600 transition-all duration-300 hover:-translate-x-0.5 hover:bg-blue-50 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </a>
            @endif

            {{-- Halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex h-9 w-9 items-center justify-center text-sm font-semibold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-600/30">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold text-slate-600 transition-all duration-300 hover:scale-110 hover:bg-blue-50 hover:text-blue-600">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-600 transition-all duration-300 hover:translate-x-0.5 hover:bg-blue-50 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            @else
                <span class="pointer-events-none flex h-9 w-9 items-center justify-center rounded-full text-slate-300" aria-disabled="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
