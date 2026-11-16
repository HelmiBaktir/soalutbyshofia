<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $courses = [
            [
                'code' => 'EKMA4116',
                'name' => 'Manajemen',
                'prodi' => 'Manajemen',
                'semester' => '1',
                'price' => 75000,
                'description' => 'Mata kuliah pengantar yang membahas fungsi-fungsi manajemen (perencanaan, pengorganisasian, pengarahan, dan pengendalian) dalam organisasi.',
            ],
            [
                'code' => 'EKMA4214',
                'name' => 'Manajemen Sumber Daya Manusia',
                'prodi' => 'Manajemen',
                'semester' => '3',
                'price' => 85000,
                'description' => 'Membahas pengelolaan SDM mulai dari rekrutmen, seleksi, pelatihan, hingga penilaian kinerja karyawan.',
            ],
            [
                'code' => 'EKSI4205',
                'name' => 'Bank dan Lembaga Keuangan Non Bank',
                'prodi' => 'Akuntansi',
                'semester' => '2',
                'price' => 80000,
                'description' => 'Memahami peran bank dan lembaga keuangan non-bank dalam perekonomian serta produk-produk keuangannya.',
            ],
            [
                'code' => 'EKSI4313',
                'name' => 'Akuntansi Keuangan Lanjutan I',
                'prodi' => 'Akuntansi',
                'semester' => '5',
                'price' => 90000,
                'description' => 'Pembahasan lanjutan akuntansi keuangan meliputi penggabungan usaha dan laporan keuangan konsolidasi.',
            ],
            [
                'code' => 'HKUM4101',
                'name' => 'Pengantar Ilmu Hukum',
                'prodi' => 'Ilmu Hukum',
                'semester' => '1',
                'price' => 70000,
                'description' => 'Pengenalan dasar-dasar ilmu hukum, sumber hukum, dan sistem hukum Indonesia.',
            ],
            [
                'code' => 'HKUM4302',
                'name' => 'Hukum Pajak',
                'prodi' => 'Ilmu Hukum',
                'semester' => '4',
                'price' => 75000,
                'description' => 'Membahas ketentuan hukum perpajakan, subjek/objek pajak, serta penyelesaian sengketa pajak.',
            ],
            [
                'code' => 'SKOM4201',
                'name' => 'Perencanaan Program Komunikasi',
                'prodi' => 'Ilmu Komunikasi',
                'semester' => '2',
                'price' => 80000,
                'description' => 'Teknik merancang dan merencanakan program komunikasi yang efektif untuk berbagai khalayak.',
            ],
            [
                'code' => 'ESPA4110',
                'name' => 'Pengantar Ekonomi Makro',
                'prodi' => 'Ekonomi Pembangunan',
                'semester' => '1',
                'price' => 65000,
                'description' => 'Konsep dasar ekonomi makro: pendapatan nasional, inflasi, pengangguran, dan kebijakan fiskal-moneter.',
            ],
            [
                'code' => 'SOSI4201',
                'name' => 'Pengantar Sosiologi',
                'prodi' => 'Sosiologi',
                'semester' => '2',
                'price' => 60000,
                'description' => 'Dasar-dasar sosiologi, interaksi sosial, struktur sosial, dan perubahan sosial masyarakat.',
            ],
            [
                'code' => 'PDGK4101',
                'name' => 'Keterampilan Berbahasa Indonesia',
                'prodi' => 'PGSD',
                'semester' => '1',
                'price' => 55000,
                'description' => 'Meningkatkan keterampilan menyimak, berbicara, membaca, dan menulis bahasa Indonesia.',
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(['code' => $course['code']], $course);
        }
    }
}
