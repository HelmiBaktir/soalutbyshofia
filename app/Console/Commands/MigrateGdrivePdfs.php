<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateGdrivePdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:gdrive-pdfs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi file PDF bank soal dari Google Drive ke storage lokal (course_files)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $folderId = config('google-drive.folder_id') ?: env('GOOGLE_DRIVE_FOLDER_ID');

        if (empty($folderId)) {
            $this->error('GOOGLE_DRIVE_FOLDER_ID belum diatur di .env.');
            return self::FAILURE;
        }

        $this->info("Membaca file PDF dari folder Google Drive: {$folderId}");

        try {
            // 'files('/')' menormalisasi ke root folder (GOOGLE_DRIVE_FOLDER_ID).
            $files = Storage::disk('google')->files('/');
        } catch (Throwable $e) {
            $this->error('Gagal terhubung ke Google Drive: '.$e->getMessage());
            Log::error('[migrate:gdrive-pdfs] Gagal membaca daftar file Google Drive.', [
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }

        // Hanya proses file berekstensi .pdf.
        $pdfs = array_values(array_filter($files, function (string $file) {
            return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf';
        }));

        if (empty($pdfs)) {
            $this->warn('Tidak ada file PDF ditemukan di folder Google Drive.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Ditemukan %d file PDF. Memulai migrasi...', count($pdfs)));

        $progress = $this->output->createProgressBar(count($pdfs));
        $progress->start();

        $imported = 0;
        $skipped = 0;

        foreach ($pdfs as $fileName) {
            // "EKMA4116.pdf" -> "EKMA4116" (kode mata kuliah).
            $code = strtoupper(pathinfo($fileName, PATHINFO_FILENAME));

            try {
                $course = Course::where('code', $code)->first();

                if (! $course) {
                    Log::warning('[migrate:gdrive-pdfs] File tidak cocok dengan kode mata kuliah, dilewati.', [
                        'file' => $fileName,
                        'expected_code' => $code,
                    ]);
                    $skipped++;
                    $progress->advance();

                    continue;
                }

                $content = Storage::disk('google')->get($fileName);

                // Disimpan ke storage/app/private/course_files/{file}.
                $relativePath = 'course_files/'.$fileName;
                Storage::disk('local')->put($relativePath, $content);

                // Satu course = satu file PDF master.
                CourseFile::updateOrCreate(
                    ['course_id' => $course->id],
                    [
                        'file_name' => $fileName,
                        'file_path' => $relativePath,
                    ]
                );

                $imported++;
            } catch (Throwable $e) {
                Log::error('[migrate:gdrive-pdfs] Gagal memproses file.', [
                    'file' => $fileName,
                    'error' => $e->getMessage(),
                ]);
                $skipped++;
            }

            $progress->advance();
        }

        $progress->finish();
        $this->newLine(2);
        $this->info(sprintf('Migrasi selesai: %d file diimpor, %d dilewati/gagal.', $imported, $skipped));

        return self::SUCCESS;
    }
}
