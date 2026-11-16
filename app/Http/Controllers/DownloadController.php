<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\DownloadLog;
use App\Models\Entitlement;
use App\Models\Order;
use App\Services\PdfWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __construct(private readonly PdfWatermarkService $watermarker)
    {
    }

    /**
     * Download untuk pengguna yang sudah login.
     */
    public function download(Request $request, Course $course): BinaryFileResponse
    {
        $user = $request->user();

        $entitlement = Entitlement::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('download_expires_at', '>', now())
            ->latest()
            ->first();

        if (! $entitlement) {
            abort(403, 'Anda tidak memiliki akses download untuk mata kuliah ini.');
        }

        return $this->serve($course, $entitlement, $user->email, $request, $user->id);
    }

    /**
     * Download untuk tamu (guest) melalui signed URL yang dikirim via email.
     */
    public function guestDownload(Request $request, Order $order, Course $course): BinaryFileResponse
    {
        $entitlement = Entitlement::query()
            ->where('order_id', $order->id)
            ->where('course_id', $course->id)
            ->where('download_expires_at', '>', now())
            ->latest()
            ->first();

        if (! $entitlement) {
            abort(403, 'Link download tidak valid atau sudah kedaluwarsa.');
        }

        $email = $order->guest_email ?? 'guest';

        return $this->serve($course, $entitlement, $email, $request, null);
    }

    private function serve(
        Course $course,
        Entitlement $entitlement,
        string $email,
        Request $request,
        ?int $userId,
    ): BinaryFileResponse {
        $courseFile = $course->files()->latest()->first();

        if (! $courseFile || ! Storage::disk('local')->exists($courseFile->file_path)) {
            abort(404, 'File PDF belum tersedia untuk mata kuliah ini.');
        }

        $watermarkText = 'banksoalutbyshofia - '.$email;

        $tempPath = $this->watermarker->watermark('local', $courseFile->file_path, $watermarkText);

        DownloadLog::create([
            'entitlement_id' => $entitlement->id,
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'downloaded_at' => now(),
        ]);

        return response()
            ->download($tempPath, $course->code.'.pdf', ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }
}
