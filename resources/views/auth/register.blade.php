@extends('layouts.app')

@section('title', 'Daftar - '.config('app.name'))

@section('content')
<div class="mx-auto max-w-md px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-extrabold text-slate-900">Daftar</h1>
        <p class="mt-1 text-sm text-slate-500">Buat akun untuk menyimpan riwayat pesanan & akses download.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" autofocus
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                <input type="password" name="password"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
            </div>
            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-700">Daftar</button>
        </form>

        <p class="mt-4 text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
