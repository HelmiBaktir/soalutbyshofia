<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseFile;
use App\Services\PdfWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', '20');

        if (! in_array($perPage, ['20', '50', '100', 'all'], true)) {
            $perPage = '20';
        }

        $query = Course::with('files')
            ->when($search = trim((string) $request->query('search')), function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('code', 'ilike', "%{$search}%")
                        ->orWhere('name', 'ilike', "%{$search}%");
                });
            })
            ->when($request->query('prodi'), fn ($q, $prodi) => $q->where('prodi', $prodi));

        $totalCourses = $query->count();

        if ($perPage === 'all') {
            $courses = $query->orderBy('prodi')->orderBy('code')->get();
            $paginated = false;
        } else {
            $courses = $query->orderBy('prodi')->orderBy('code')->paginate((int) $perPage)->withQueryString();
            $paginated = true;
        }

        $prodis = Course::query()->distinct()->orderBy('prodi')->pluck('prodi');

        return view('admin.courses.index', compact('courses', 'prodis', 'totalCourses', 'perPage', 'paginated'));
    }

    public function create()
    {
        return view('admin.courses.form', ['course' => new Course()]);
    }

    public function store(Request $request)
    {
        $course = Course::create($this->validated($request));

        $this->storeImage($request, $course);
        $this->storePdf($request, $course);

        return redirect()->route('admin.courses.index')->with('status', 'Mata kuliah ditambahkan.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.form', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validated($request, $course));

        $this->storeImage($request, $course);
        $this->storePdf($request, $course);

        return redirect()->route('admin.courses.index')->with('status', 'Mata kuliah diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', 'Mata kuliah dihapus.');
    }

    public function export()
    {
        $courses = Course::query()->orderBy('code')->get(['code', 'name', 'prodi', 'semester', 'price', 'jumlah_soal', 'masa_ujian']);

        $fileName = 'mata-kuliah-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        return response()->stream(function () use ($courses) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM agar Excel membaca huruf dengan benar
            fputcsv($out, ['code', 'name', 'prodi', 'semester', 'price', 'jumlah_soal', 'masa_ujian']);

            foreach ($courses as $c) {
                fputcsv($out, [
                    $c->code,
                    $c->name,
                    $c->prodi,
                    $c->semester,
                    (string) $c->price,
                    (string) $c->jumlah_soal,
                    $c->masa_ujian,
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:5120']]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');

        if ($handle === false) {
            return back()->with('status', 'Gagal membuka file CSV.');
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return back()->with('status', 'File CSV kosong.');
        }

        $header = array_map(fn ($h) => strtolower(trim((string) preg_replace('/^\xEF\xBB\xBF/', '', (string) $h))), $header);

        $codeIndex = array_search('code', $header, true);
        $priceIndex = array_search('price', $header, true);
        $nameIndex = array_search('name', $header, true);
        $prodiIndex = array_search('prodi', $header, true);
        $semesterIndex = array_search('semester', $header, true);
        $jumlahIndex = array_search('jumlah_soal', $header, true);
        $masaIndex = array_search('masa_ujian', $header, true);

        if ($codeIndex === false || $priceIndex === false) {
            fclose($handle);

            return back()->with('status', 'Kolom "code" dan "price" wajib ada di file CSV.');
        }

        $updated = 0;
        $notFound = [];

        while (($row = fgetcsv($handle)) !== false) {
            $code = strtoupper(trim((string) ($row[$codeIndex] ?? '')));

            if ($code === '') {
                continue;
            }

            $course = Course::where('code', $code)->first();

            if (! $course) {
                $notFound[] = $code;

                continue;
            }

            $data = [
                'price' => (int) preg_replace('/\D/', '', (string) ($row[$priceIndex] ?? 0)),
            ];

            if ($nameIndex !== false && trim((string) ($row[$nameIndex] ?? '')) !== '') {
                $data['name'] = trim((string) $row[$nameIndex]);
            }

            if ($prodiIndex !== false && trim((string) ($row[$prodiIndex] ?? '')) !== '') {
                $data['prodi'] = trim((string) $row[$prodiIndex]);
            }

            if ($semesterIndex !== false && trim((string) ($row[$semesterIndex] ?? '')) !== '') {
                $data['semester'] = trim((string) $row[$semesterIndex]);
            }

            if ($jumlahIndex !== false && trim((string) ($row[$jumlahIndex] ?? '')) !== '') {
                $data['jumlah_soal'] = (int) preg_replace('/\D/', '', (string) $row[$jumlahIndex]);
            }

            if ($masaIndex !== false && trim((string) ($row[$masaIndex] ?? '')) !== '') {
                $data['masa_ujian'] = trim((string) $row[$masaIndex]);
            }

            $course->update($data);
            $updated++;
        }

        fclose($handle);

        $message = "Import selesai: {$updated} mata kuliah diperbarui.";

        if (! empty($notFound)) {
            $message .= ' Kode tidak ditemukan: '.implode(', ', array_slice($notFound, 0, 8)).(count($notFound) > 8 ? ' +'.(count($notFound) - 8).' lagi' : '');
        }

        return back()->with('status', $message);
    }

    public function preview(Course $course)
    {
        $file = $course->files()->latest()->first();

        if (! $file || ! Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'PDF belum tersedia.');
        }

        return response()->file(Storage::disk('local')->path($file->file_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function previewWatermark(Course $course)
    {
        $file = $course->files()->latest()->first();

        if (! $file || ! Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'PDF belum tersedia.');
        }

        $watermarked = app(PdfWatermarkService::class)->watermark(
            'local',
            $file->file_path,
            'banksoalutbyshofia - contoh@email.com'
        );

        return response()->file($watermarked, ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }

    public function previewUpload(Request $request)
    {
        $request->validate(['pdf' => ['required', 'file', 'mimes:pdf', 'max:20480']]);

        $tmpPath = $request->file('pdf')->store('temp_uploads', 'local');

        $watermarked = app(PdfWatermarkService::class)->watermark(
            'local',
            $tmpPath,
            'banksoalutbyshofia - contoh@email.com'
        );

        $contents = file_get_contents($watermarked);
        @unlink($watermarked);
        Storage::disk('local')->delete($tmpPath);

        return response($contents, 200, ['Content-Type' => 'application/pdf']);
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('courses', 'code')->ignore($course?->id)],
            'name' => ['required', 'string', 'max:255'],
            'prodi' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:20'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'jumlah_soal' => ['nullable', 'integer', 'min:0'],
            'masa_ujian' => ['nullable', 'string', 'max:255'],
            'is_bundle' => ['sometimes', 'boolean'],
        ]);
    }

    private function storeImage(Request $request, Course $course): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $request->validate(['image' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048']]);

        $path = $request->file('image')->store('courses', 'public');

        $course->update(['image' => $path]);
    }

    private function storePdf(Request $request, Course $course): void
    {
        if (! $request->hasFile('pdf')) {
            return;
        }

        $request->validate(['pdf' => ['file', 'mimes:pdf', 'max:20480']]);

        $fileName = $course->code.'.pdf';
        $path = $request->file('pdf')->storeAs('course_files', $fileName, 'local');

        CourseFile::updateOrCreate(
            ['course_id' => $course->id],
            ['file_name' => $fileName, 'file_path' => $path]
        );
    }
}
