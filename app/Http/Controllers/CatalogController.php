<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%")
                    ->orWhere('prodi', 'ilike', "%{$search}%");
            });
        }

        if ($prodi = $request->query('prodi')) {
            $query->where('prodi', $prodi);
        }

        if ($semester = $request->query('semester')) {
            $query->where('semester', $semester);
        }

        $courses = $query->orderBy('code')->paginate(12)->withQueryString();

        $prodis = Course::distinct()->orderBy('prodi')->pluck('prodi');
        $semesters = Course::distinct()->whereNotNull('semester')->orderBy('semester')->pluck('semester');

        return view('catalog.index', compact('courses', 'prodis', 'semesters'));
    }

    public function show(Course $course)
    {
        $course->load('files');

        $entitlement = null;

        if (auth()->check()) {
            $entitlement = auth()->user()->entitlements()
                ->where('course_id', $course->id)
                ->where('download_expires_at', '>', now())
                ->latest()
                ->first();
        }

        return view('catalog.show', [
            'course' => $course,
            'entitlement' => $entitlement,
            'downloadUrl' => $entitlement?->downloadUrl(),
        ]);
    }
}
