@extends('layouts.app')

@section('title', 'Masuk - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-md px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-extrabold text-slate-900">Masuk</h1>
        <p class="mt-1 text-sm text-slate-500">Lanjutkan untuk mengakses dashboard & download.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                <input type="password" name="password"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">Masuk</button>
        </form>

        <div class="mt-5 border-t border-slate-100 pt-4">
            <p class="text-center text-sm text-slate-500">Belum punya akun?</p>
            <a href="{{ route('register') }}" class="mt-2 block w-full rounded-xl border border-blue-200 px-4 py-2.5 text-center text-sm font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50">
                Daftar Akun Baru
            </a>
        </div>
    </div>
</div>
@endsection
