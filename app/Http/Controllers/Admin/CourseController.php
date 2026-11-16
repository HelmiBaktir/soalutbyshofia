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
        $courses = Course::with('files')
            ->when($search = trim((string) $request->query('search')), function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('code', 'ilike', "%{$search}%")
                        ->orWhere('name', 'ilike', "%{$search}%");
                });
            })
            ->when($request->query('prodi'), fn ($q, $prodi) => $q->where('prodi', $prodi))
            ->get()
            ->sortBy(fn ($c) => strtolower($c->prodi).sprintf('%03d', (int) $c->semester))
            ->groupBy('prodi');

        $totalCourses = $courses->flatten()->count();

        $prodis = Course::query()->distinct()->orderBy('prodi')->pluck('prodi');

        return view('admin.courses.index', compact('courses', 'prodis', 'totalCourses'));
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
