@extends('admin.layout')

@section('title', 'Mata Kuliah')

@section('admin_content')
<div x-data="{ preview: false, previewUrl: '', previewTitle: '', confirmDelete: false, deleteUrl: '' }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Mata Kuliah</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $totalCourses }} mata kuliah.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700">+ Tambah</a>
    </div>

    <form method="GET" action="{{ route('admin.courses.index') }}" class="mt-5 flex flex-wrap items-center gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama mata kuliah..." class="w-72 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
        <select name="prodi" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-400">
            <option value="">Semua Prodi</option>
            @foreach ($prodis as $prodi)
                <option value="{{ $prodi }}" {{ request('prodi') === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700">Cari</button>
        @if (request()->filled('search') || request()->filled('prodi'))
            <a href="{{ route('admin.courses.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 transition-colors hover:bg-slate-100">Reset</a>
        @endif
    </form>
    <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Semester</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">PDF</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($courses as $prodi => $group)
                    <tr class="bg-slate-50/80">
                        <td colspan="6" class="px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold uppercase tracking-wide text-slate-700">{{ $prodi }}</span>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700">{{ $group->count() }} MK</span>
                            </div>
                        </td>
                    </tr>
                    @foreach ($group as $course)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-4 py-3 pl-8 font-bold text-blue-600">{{ $course->code }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">{{ $course->name }}</p>
                                @if ($course->is_bundle)
                                    <span class="mt-0.5 inline-block rounded-full bg-violet-100 px-2 py-0.5 text-[11px] font-semibold text-violet-600">Bundle</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-md bg-slate-100 px-1.5 text-xs font-bold text-slate-600">Semester {{ $course->semester ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-800">Rp {{ number_format($course->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($course->files->isNotEmpty())
                                    <button type="button" @click="previewUrl = '{{ route('admin.courses.preview-watermark', $course) }}'; previewTitle = '{{ $course->code }} — {{ $course->name }}'; preview = true"
                                        class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-semibold text-blue-600 transition-colors hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        Preview
                                    </button>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.courses.edit', $course) }}" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-blue-600 shadow-sm transition-colors hover:border-blue-200 hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                                    </a>
                                    <button type="button" @click="deleteUrl = '{{ route('admin.courses.destroy', $course) }}'; confirmDelete = true" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-red-600 shadow-sm transition-colors hover:border-red-200 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">Tidak ada mata kuliah yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div x-show="preview" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/70" @click="preview = false"></div>
        <div class="relative flex h-[88vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 bg-slate-50 px-5 py-4">
                <div class="min-w-0">
                    <h3 class="truncate font-bold text-slate-900" x-text="previewTitle"></h3>
                    <p class="mt-0.5 text-xs text-slate-500">Pratinjau dokumen PDF dengan watermark — gunakan scroll untuk membaca seluruh halaman.</p>
                </div>
                <button type="button" @click="preview = false" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm transition-colors hover:bg-slate-100 hover:text-slate-700">&times;</button>
            </div>
            <div class="flex-1 bg-slate-200/60">
                <iframe :src="previewUrl" class="h-full w-full" title="Preview PDF"></iframe>
            </div>
        </div>
    </div>

    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/70" @click="confirmDelete = false"></div>
        <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
            </div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Hapus Mata Kuliah?</h3>
            <p class="mt-1 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" @click="confirmDelete = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</button>
                <form method="POST" :action="deleteUrl">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


