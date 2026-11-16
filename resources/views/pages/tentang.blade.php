@extends('layouts.app')

@section('title', 'Tentang Kami - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">

    <div class="text-center">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Tentang Kami</h1>
        <p class="mt-3 text-slate-500">Mengenal lebih dekat Bank Soal UT By Shofia.</p>
    </div>

    <div class="mt-10 space-y-10 leading-relaxed text-slate-600">
        <section>
            <h2 class="text-xl font-bold text-slate-900">Siapa Kami</h2>
            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vel lectus ut nibh tincidunt blandit.
                Curabitur sed risus id nunc fermentum convallis. Vestibulum ante ipsum primis in faucibus orci luctus
                et ultrices posuere cubilia curae.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-slate-900">Apa yang Kami Tawarkan</h2>
            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus
                ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-slate-900">Visi &amp; Misi</h2>
            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi fringilla convallis sapien, id pulvinar
                odio volutpat. Praesent elementum hendrerit tortor. Sed at augue ut justo pretium tincidunt.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-slate-900">Hubungi Kami</h2>
            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam felis, ultricies nec, pellentesque eu,
                pretium quis, sem.
            </p>
            <a href="{{ route('page.contact') }}" class="mt-5 inline-block rounded-xl bg-blue-600 px-6 py-2.5 font-semibold text-white transition-all duration-300 hover:bg-blue-700">
                Hubungi Kami
            </a>
        </section>
    </div>
</div>
@endsection
