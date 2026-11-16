@extends('admin.layout')

@section('title', 'Pengguna')

@section('admin_content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $users->total() }} pengguna.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mt-5 flex flex-wrap items-center gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
        <select name="role" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-400">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
        </select>
        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700">Filter</button>
        @if (request()->filled('search') || request()->filled('role'))
            <a href="{{ route('admin.users.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 transition-colors hover:bg-slate-100">Reset</a>
        @endif
    </form>
    <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-4 py-3"><x-sort-link column="name" label="Nama" /></th>
                    <th class="px-4 py-3"><x-sort-link column="email" label="Email" /></th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3"><x-sort-link column="orders_count" label="Order" /></th>
                    <th class="px-4 py-3"><x-sort-link column="entitlements_count" label="Akses" /></th>
                    <th class="px-4 py-3"><x-sort-link column="created_at" label="Terdaftar" /></th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $user->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                    @if ($user->id === auth()->id())
                                        <span class="text-xs text-blue-500">(Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $user->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $user->isAdmin() ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->orders_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->entitlements_count }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($user->id !== auth()->id())
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="role" value="{{ $user->isAdmin() ? 'user' : 'admin' }}">
                                        <button type="submit" title="{{ $user->isAdmin() ? 'Jadikan User' : 'Jadikan Admin' }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white shadow-sm transition-colors {{ $user->isAdmin() ? 'text-amber-600 hover:border-amber-200 hover:bg-amber-50' : 'text-blue-600 hover:border-blue-200 hover:bg-blue-50' }}">
                                            @if ($user->isAdmin())
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0 6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-red-600 shadow-sm transition-colors hover:border-red-200 hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">Tidak ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection

