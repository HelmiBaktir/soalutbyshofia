@extends('admin.layout')

@section('title', $course->exists ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah')

@section('admin_content')
<form method="POST" action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}" enctype="multipart/form-data"
    x-data="{
        previewUrl: '{{ $course->files->isNotEmpty() ? route('admin.courses.preview-watermark', $course) : '' }}',
        isWatermarked: {{ $course->files->isNotEmpty() ? 'true' : 'false' }},
        loading: false,
        pdfName: '{{ $course->files->isNotEmpty() ? $course->files->first()->file_name : '' }}',
        pdfDragging: false,
        imgDragging: false,
        imgName: '',
        imgPreview: '{{ $course->image ? asset('storage/'.$course->image) : '' }}',
        priceDisplay: '{{ old('price', $course->price) ? number_format((int) old('price', $course->price), 0, '.', '.') : '' }}',
        priceRaw: '{{ old('price', $course->price) }}',
        formatPrice() {
            const digits = this.priceDisplay.replace(/\D/g, '');
            this.priceRaw = digits || '0';
            this.priceDisplay = new Intl.NumberFormat('id-ID').format(this.priceRaw);
        },
        async handlePdf(file) {
            if (!file) return;
            this.pdfName = file.name;
            this.loading = true;
            try {
                const fd = new FormData();
                fd.append('pdf', file);
                const resp = await fetch('{{ route('admin.courses.preview-upload') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: fd,
                });
                if (resp.ok) {
                    const blob = await resp.blob();
                    this.previewUrl = URL.createObjectURL(blob);
                    this.isWatermarked = true;
                } else {
                    this.previewUrl = URL.createObjectURL(file);
                    this.isWatermarked = false;
                }
            } catch (e) {
                this.previewUrl = URL.createObjectURL(file);
                this.isWatermarked = false;
            }
            this.loading = false;
        },
        handleImg(file) {
            if (!file) return;
            this.imgName = file.name;
            this.imgPreview = URL.createObjectURL(file);
        }
    }">
    @csrf
    @if ($course->exists) @method('PUT') @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">{{ $course->exists ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Lengkapi data mata kuliah beserta file PDF-nya.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mt-5 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-600">
            <p class="font-semibold">Periksa kembali isian berikut:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 grid gap-6 grid-cols-5">
        <div class="space-y-6 col-span-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-900">Informasi Mata Kuliah</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Kode MK</label>
                        <input type="text" name="code" value="{{ old('code', $course->code) }}" placeholder="contoh: EKMA4116" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                        <p class="mt-1 text-xs text-slate-400">Kode unik mata kuliah.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Prodi</label>
                        <input type="text" name="prodi" value="{{ old('prodi', $course->prodi) }}" placeholder="contoh: Manajemen" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                        <p class="mt-1 text-xs text-slate-400">Nama program studi.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Mata Kuliah</label>
                        <input type="text" name="name" value="{{ old('name', $course->name) }}" placeholder="contoh: Manajemen Sumber Daya Manusia" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                        <p class="mt-1 text-xs text-slate-400">Nama lengkap mata kuliah.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Semester</label>
                        <input type="text" name="semester" value="{{ old('semester', $course->semester) }}" placeholder="contoh: 3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <p class="mt-1 text-xs text-slate-400">Opsional.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Harga (Rp)</label>
                        <input type="text" inputmode="numeric" x-model="priceDisplay" @input="formatPrice()" placeholder="contoh: 75.000" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100" required>
                        <input type="hidden" name="price" :value="priceRaw">
                        <p class="mt-1 text-xs text-slate-400">Pemisah ribuan otomatis, mis. 75.000.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Jumlah Soal</label>
                        <input type="number" name="jumlah_soal" value="{{ old('jumlah_soal', $course->jumlah_soal) }}" min="0" placeholder="contoh: 150" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <p class="mt-1 text-xs text-slate-400">Opsional. Tampil di halaman detail.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Masa Ujian</label>
                        <input type="text" name="masa_ujian" value="{{ old('masa_ujian', $course->masa_ujian) }}" placeholder="contoh: 2020.1 – 2024.2" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <p class="mt-1 text-xs text-slate-400">Opsional. Contoh: 2020–2024.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label>
                        <textarea name="description" rows="4" placeholder="Jelaskan isi mata kuliah ini..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ old('description', $course->description) }}</textarea>
                        <p class="mt-1 text-xs text-slate-400">Opsional.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">File PDF</h2>
                    @if ($course->files->isNotEmpty())
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">PDF tersedia</span>
                    @endif
                </div>
                <div class="mt-3 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
                    PDF otomatis diberi <strong>watermark email pembeli</strong> (diagonal berulang). Maksimal 20 MB.
                </div>
                <label for="pdfInput"
                    @dragover.prevent="pdfDragging = true" @dragleave.prevent="pdfDragging = false" @drop.prevent="pdfDragging = false; handlePdf($event.dataTransfer.files[0])"
                    class="mt-3 flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl border-2 border-dashed px-6 py-8 text-center transition-colors"
                    :class="pdfDragging ? 'border-blue-400 bg-blue-50' : 'border-slate-300 hover:border-blue-300 hover:bg-slate-50'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" /></svg>
                    <p class="text-sm font-semibold text-slate-700" x-show="!pdfName" x-cloak>Tarik &amp; letakkan PDF di sini</p>
                    <p class="text-sm font-semibold text-emerald-600" x-show="pdfName" x-cloak x-text="pdfName"></p>
                    <p class="text-xs text-slate-400" x-show="!pdfName" x-cloak>atau <span class="font-semibold text-blue-600">klik untuk memilih file</span></p>
                    <p class="text-xs text-slate-400" x-show="pdfName" x-cloak>klik untuk mengganti file</p>
                    <input id="pdfInput" type="file" name="pdf" accept="application/pdf" class="hidden" @change="handlePdf($event.target.files[0])">
                </label>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-900">Gambar Sampul</h2>
                <p class="mt-1 text-xs text-slate-400">Opsional. Format JPG/PNG/WebP, maksimal 2 MB.</p>
                <label for="imgInput"
                    @dragover.prevent="imgDragging = true" @dragleave.prevent="imgDragging = false" @drop.prevent="imgDragging = false; handleImg($event.dataTransfer.files[0])"
                    class="mt-3 flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl border-2 border-dashed px-6 py-8 text-center transition-colors"
                    :class="imgDragging ? 'border-blue-400 bg-blue-50' : 'border-slate-300 hover:border-blue-300 hover:bg-slate-50'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    <p class="text-sm font-semibold text-slate-700" x-show="!imgName" x-cloak>Tarik &amp; letakkan gambar di sini</p>
                    <p class="text-sm font-semibold text-emerald-600" x-show="imgName" x-cloak x-text="imgName"></p>
                    <p class="text-xs text-slate-400" x-show="!imgName" x-cloak>atau <span class="font-semibold text-blue-600">klik untuk memilih file</span></p>
                    <p class="text-xs text-slate-400" x-show="imgName" x-cloak>klik untuk mengganti file</p>
                    <input id="imgInput" type="file" name="image" accept="image/*" class="hidden" @change="handleImg($event.target.files[0])">
                </label>
                <img :src="imgPreview" x-show="imgPreview" x-cloak class="mt-3 h-24 w-24 rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <input type="checkbox" name="is_bundle" value="1" {{ old('is_bundle', $course->is_bundle) ? 'checked' : '' }} class="h-5 w-5 rounded text-blue-600">
                <span>
                    <span class="block text-sm font-semibold text-slate-800">Tandai sebagai Bundle</span>
                    <span class="block text-xs text-slate-400">Paket berisi beberapa mata kuliah.</span>
                </span>
            </label>
        </div>
        <div class="col-span-2">
            <div class="sticky top-16 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4">
                    <div>
                        <h2 class="font-bold text-slate-900">Pratinjau PDF</h2>
                        <p class="text-xs text-slate-500">Hasil dengan watermark</p>
                    </div>
                    <span x-show="previewUrl && !loading" x-cloak class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold"
                        :class="isWatermarked ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                        x-text="isWatermarked ? 'Watermark Aktif' : 'File Mentah'"></span>
                </div>
                <div class="h-[calc(100vh_-_5rem)] bg-slate-200/60">
                    <template x-if="loading">
                        <div class="flex h-full flex-col items-center justify-center p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 animate-spin text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                            <p class="mt-3 text-sm font-semibold text-slate-600">Memproses watermark...</p>
                        </div>
                    </template>
                    <template x-if="!loading && previewUrl">
                        <iframe :src="previewUrl" class="h-full w-full" title="Pratinjau PDF"></iframe>
                    </template>
                    <template x-if="!loading && !previewUrl">
                        <div class="flex h-full flex-col items-center justify-center p-8 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            </div>
                            <p class="mt-4 font-semibold text-slate-700">Belum ada PDF</p>
                            <p class="mt-1 max-w-xs text-sm text-slate-400">Upload file PDF untuk melihat pratinjau dengan watermark.</p>
                        </div>
                    </template>
                </div>
                <div class="border-t border-slate-100 bg-white px-5 py-3 text-center text-xs text-slate-400">
                    Watermark (email pembeli) tampil diagonal berulang di seluruh halaman.
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2.5 font-semibold text-white shadow-sm transition-all hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.courses.index') }}" class="rounded-xl border border-slate-200 px-6 py-2.5 font-semibold text-slate-600 transition-all hover:bg-slate-50">Batal</a>
    </div>
</form>
@endsection



