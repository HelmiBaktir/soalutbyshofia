<?php

namespace App\Services;

use App\Support\PdfXrefNormalizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use RuntimeException;

class PdfWatermarkService
{
    /**
     * Bubuhkan teks watermark transparan pada setiap halaman PDF dan
     * kembalikan path file sementara hasil watermark.
     *
     * @param  string  $disk  Nama disk sumber PDF (mis. "local").
     * @param  string  $path  Path relatif file PDF di disk tersebut.
     * @param  string  $text  Teks watermark.
     */
    public function watermark(string $disk, string $path, string $text): string
    {
        $sourcePath = Storage::disk($disk)->path($path);

        if (! is_file($sourcePath)) {
            throw new RuntimeException("File PDF tidak ditemukan: {$path}");
        }

        $tempDir = storage_path('app/public/temp');

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $normalizedPath = null;

        try {
            $mpdf = new Mpdf(['tempDir' => $tempDir]);

            try {
                $pageCount = $mpdf->setSourceFile($sourcePath);
            } catch (\Throwable $e) {
                // PDF modern (xref stream) -> normalisasi ke classic xref dulu.
                $normalizedPath = (new PdfXrefNormalizer())->normalize($sourcePath);
                $mpdf = new Mpdf(['tempDir' => $tempDir]);
                $pageCount = $mpdf->setSourceFile($normalizedPath);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $mpdf->importPage($pageNo);

                if ($pageNo > 1) {
                    $mpdf->AddPage();
                }

                $mpdf->useTemplate($templateId, ['adjustPageSize' => true]);

                $this->stampTiled($mpdf, $text);
            }

            $tempPath = $tempDir.DIRECTORY_SEPARATOR.'wm_'.uniqid('', true).'.pdf';

            $mpdf->Output($tempPath, 'F');

            $this->cleanup($normalizedPath);

            return $tempPath;
        } catch (\Throwable $e) {
            $this->cleanup($normalizedPath);
            // PDF modern (cross-reference stream) tidak didukung parser gratis.
            // Tetap unduh PDF apa adanya (tanpa watermark) agar tidak gagal.
            Log::warning('[PdfWatermarkService] Watermark gagal, unduh tanpa watermark.', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            $tempPath = $tempDir.DIRECTORY_SEPARATOR.'orig_'.uniqid('', true).'.pdf';
            copy($sourcePath, $tempPath);

            return $tempPath;
        }
    }

    /**
     * Gambar watermark diagonal berulang (tiled) pada halaman aktif.
     */
    protected function stampTiled(Mpdf $mpdf, string $text): void
    {
        $fontSize = 12; // pt
        $gap = 30;      // mm, jarak kosong antar teks (searah diagonal)
        $angle = 45;    // derajat

        $mpdf->SetFont('helvetica', 'B', $fontSize);
        $textWidth = $mpdf->GetStringWidth($text);

        $mpdf->SetTextColor(153, 153, 153);
        $mpdf->SetAlpha(0.3);

        $spacingX = $textWidth + $gap;
        $spacingY = 25; // mm

        $cx = $mpdf->w / 2;
        $cy = $mpdf->h / 2;
        $half = ($mpdf->w + $mpdf->h) / 2;

        $mpdf->Rotate($angle, $cx, $cy);

        for ($y = -$half; $y <= $half; $y += $spacingY) {
            for ($x = -$half; $x <= $half; $x += $spacingX) {
                $mpdf->Text($cx + $x, $cy + $y, $text);
            }
        }

        $mpdf->Rotate(0);
        $mpdf->SetAlpha(1);
    }

    protected function cleanup(?string $path): void
    {
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }
}
