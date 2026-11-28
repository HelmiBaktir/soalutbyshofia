<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportLocalPdfs extends Command
{
    protected $signature = 'import:local-pdfs {--dir=storage/app/import/pdfs}';

    protected $description = 'Import PDF bank soal dari folder lokal (hasil download Drive) ke courses + course_files';

    /**
     * Mapping prefix kode (4 huruf pertama) -> prodi.
     */
    protected array $prodiMap = [
        'EACC' => 'Akuntansi',
        'ECON' => 'Ekonomi Pembangunan',
        'EMBS' => 'Manajemen',
        'ESHA' => 'Ekonomi Syariah',
        'EPFA' => 'Mata Kuliah Umum',
        'FSIH' => 'Ilmu Hukum',
        'FSIK' => 'Ilmu Komunikasi',
        'FSAP' => 'Administrasi Publik',
        'FSAB' => 'Administrasi Bisnis',
        'FSPE' => 'Ilmu Pemerintahan',
        'FSSP' => 'Administrasi Publik',
        'SPGK' => 'PGSD',
        'SPAD' => 'PGPAUD',
        'SPIK' => 'Pendidikan',
        'SPEK' => 'Pendidikan Ekonomi',
        'SPIN' => 'Pendidikan Bahasa Indonesia',
        'SPKN' => 'Sosiologi',
        'SPTP' => 'Teknologi Pendidikan',
        'SPDA' => 'Pendidikan Luar Biasa',
        'STDA' => 'Sains Data',
        'STSI' => 'Sistem Informasi',
        'MKWN' => 'Mata Kuliah Umum',
        'MKDI' => 'Mata Kuliah Umum',
        'MKKI' => 'Mata Kuliah Umum',
    ];

    /**
     * Harga per prodi (Rp). Isi sesuai daftar harga dari klien.
     * Prodi yang tidak tercantum akan memakai $defaultPrice.
     */
    protected array $prodiPrices = [
        // 'Akuntansi' => 25000,
        // 'Manajemen' => 25000,
        // 'Ilmu Hukum' => 30000,
        // ... dst
    ];

    protected int $defaultPrice = 75000;

    public function handle(): int
    {
        $dir = base_path($this->option('dir'));

        if (! is_dir($dir)) {
            $this->error("Direktori tidak ditemukan: {$dir}");

            return self::FAILURE;
        }

        $files = $this->scanPdfs($dir);

        $this->info('Ditemukan '.count($files).' file PDF.');

        if (empty($files)) {
            return self::SUCCESS;
        }

        $imported = 0;
        $skipped = 0;
        $progress = $this->output->createProgressBar(count($files));
        $progress->start();

        foreach ($files as $file) {
            $parsed = $this->parseFileName(basename($file));

            if (! $parsed) {
                $this->newLine();
                $this->warn('Tidak bisa parse nama file: '.basename($file));
                $skipped++;
                $progress->advance();

                continue;
            }

            [$code, $name] = $parsed;
            $prefix = substr($code, 0, 4);
            $prodi = $this->prodiMap[$prefix] ?? 'Mata Kuliah Umum';
            $price = $this->prodiPrices[$prodi] ?? $this->defaultPrice;

            // Simpan ke storage/app/private/course_files/{code}.pdf
            $target = 'course_files/'.$code.'.pdf';
            Storage::disk('local')->put($target, file_get_contents($file));

            $course = Course::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'prodi' => $prodi,
                    'price' => $price,
                ]
            );

            CourseFile::updateOrCreate(
                ['course_id' => $course->id],
                [
                    'file_name' => $code.'.pdf',
                    'file_path' => $target,
                ]
            );

            $imported++;
            $progress->advance();
        }

        $progress->finish();
        $this->newLine(2);
        $this->info(sprintf('Selesai: %d diimpor, %d dilewati.', $imported, $skipped));
        $this->info('Total course: '.Course::count());
        $this->info('Total course_file: '.CourseFile::count());

        return self::SUCCESS;
    }

    private function scanPdfs(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'pdf') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function parseFileName(string $fileName): ?array
    {
        if (preg_match('/^([A-Z]{4}\d{4})\s+(.+?)\s*(?:ESSAY)?\s*-\s*Update.*\.pdf$/i', $fileName, $m)) {
            return [strtoupper($m[1]), trim($m[2])];
        }

        return null;
    }
}
