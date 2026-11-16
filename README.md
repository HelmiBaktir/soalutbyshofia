# Toko Latihan Soal UT

Website toko penjualan latihan soal (bank soal PDF) mata kuliah Universitas Terbuka.
Dibangun dengan **Laravel 12** + **PostgreSQL** + **Blade/Tailwind/Alpine.js**.

## Tech Stack

- PHP 8.2+ (di mesin ini pakai **PHP 8.3** via Laragon)
- Laravel 12
- PostgreSQL 16
- Blade + Tailwind CSS + Alpine.js
- Package pihak ketiga:
  - [`klytron/laravel-google-drive-filesystem`](https://github.com/klytron/laravel-google-drive-filesystem) — integrasi Google Drive
  - FinPay gateway (ditambahkan pada modul berikutnya)

## Requirements

- PHP 8.2+, Composer
- PostgreSQL (aktif di port `5432`)
- Node.js + npm (untuk asset build)

> **Penting (Windows/Laragon):** PHP aktif di `PATH` masih `7.4`. Selalu jalankan
> `artisan`/`composer` dengan path PHP 8.3:
>
> ```powershell
> & 'C:\laragon\bin\php\php-8.3.33-nts-Win32-vs16-x64\php.exe' artisan ...
> & 'C:\laragon\bin\php\php-8.3.33-nts-Win32-vs16-x64\php.exe' 'C:\laragon\bin\composer\composer.phar' ...
> ```

> **Catatan Composer:** `optimize-autoloader` di `composer.json` sengaja di-set `false`
> karena package `google/apiclient-services` berisi ±37 ribu file yang membuat
> generate classmap sangat lambat di Windows.

## Setup

1. Clone dan install dependency:

   ```bash
   composer install
   npm install
   ```

2. Salin `.env` dan atur kredensial (lihat bagian-bagian di bawah):

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Konfigurasi PostgreSQL di `.env`:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=latihan_soal_ut
   DB_USERNAME=postgres
   DB_PASSWORD=...
   ```

4. Jalankan migration + seeder:

   ```bash
   php artisan migrate --seed
   ```

## Akun Seeder

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@example.com` | `password` |
| User | `budi@example.com` | `password` |
| User | `siti@example.com` | `password` |

Seeder juga mengisi 10 mata kuliah contoh dan 3 order (pending/paid/expired),
termasuk 1 entitlement yang kedaluwarsa (dibuat dengan `Carbon::now()->subMonths(4)`).

---

## Migrasi PDF dari Google Drive

Command `php artisan migrate:gdrive-pdfs` membaca file PDF dari sebuah folder
Google Drive, mencocokkan nama file dengan kode mata kuliah
(`EKMA4116.pdf` → course `EKMA4116`), menyalinnya ke
`storage/app/private/course_files`, lalu menyimpan path ke tabel `course_files`.
File yang tidak cocok akan dicatat di log dan dilewati.

### 1. Mendapatkan kredensial Google Drive (OAuth 2.0)

> Package ini memakai **OAuth 2.0 tokens** (bukan Service Account JSON).

1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat project baru, lalu buka **APIs & Services > Library** dan **Enable** `Google Drive API`.
3. Buka **APIs & Services > Credentials > Create Credentials > OAuth client ID**.
   - Pilih **Desktop app** (direkomendasikan untuk server) lalu buat.
   - Catat **Client ID** dan **Client Secret**.
4. Buka [OAuth 2.0 Playground](https://developers.google.com/oauthplayground/).
   - Klik ikon gerigi (kanan atas) → centang **Use your own OAuth credentials**,
     isi Client ID & Client Secret.
   - Di **Step 1**, masukkan scope `https://www.googleapis.com/auth/drive.readonly`
     (cukup untuk membaca & menyalin PDF), lalu **Authorize APIs**.
   - Di **Step 2**, klik **Exchange authorization code for tokens**.
   - Salin **Access token** dan **Refresh token**.

### 2. Mendapatkan Folder ID & sharing folder

1. Buat/gunakan folder di Google Drive yang berisi file PDF bank soal.
2. Buka folder tersebut, salin ID dari URL:
   `https://drive.google.com/drive/folders/<FOLDER_ID>`.
3. Karena kredensial OAuth milik akun Google Anda sendiri, pastikan akun tersebut
   punya akses ke folder. Jika folder dimiliki akun lain, **Share** folder ke
   email akun yang dipakai saat otorisasi OAuth (minimal akses **Viewer**).

### 3. Isi `.env`

```env
GOOGLE_DRIVE_CLIENT_ID=your-client-id
GOOGLE_DRIVE_CLIENT_SECRET=your-client-secret
GOOGLE_DRIVE_REDIRECT_URI=http://localhost
GOOGLE_DRIVE_ACCESS_TOKEN=your-access-token
GOOGLE_DRIVE_REFRESH_TOKEN=your-refresh-token
GOOGLE_DRIVE_FOLDER_ID=your-folder-id
GOOGLE_DRIVE_SCOPES=https://www.googleapis.com/auth/drive.readonly
GOOGLE_DRIVE_TEAM_DRIVE=false
GOOGLE_DRIVE_DEBUG=false
GOOGLE_DRIVE_LOG_PAYLOAD=false
```

### 4. Jalankan migrasi

```bash
php artisan migrate:gdrive-pdfs
```

Opsional, cek kredensial tanpa efek samping:

```bash
php artisan google-drive:check
```

---

## Integrasi Payment Gateway FinPay (scaffold)

Integrasi FinPay sudah disiapkan sebagai **scaffold** — belum memanggil API asli
karena kredensial merchant belum tersedia. Struktur yang tersedia:

- `config/finpay.php` — konfigurasi (base URL, merchant id/key/secret, endpoint).
- `app/Services/FinpayService.php` — `initiatePayment()` & `verifyPayment()`
  (ada TODO: isi pemanggilan HTTP & signature sesuai dokumentasi resmi FinPay).
- `app/Services/CheckoutService.php` — buat order pending, validasi **maksimal 8
  mata kuliah per akun**, tandai paid & buat entitlement (+3 bulan).
- `app/Http/Controllers/PaymentController.php` — `checkout`, `callback`, `success`.
- `app/Mail/PaymentSuccessMail.php` — email notifikasi pembayaran (driver `log`).

### Variabel `.env`

```env
FINPAY_BASE_URL=https://api.finpay.co.id
FINPAY_MERCHANT_ID=
FINPAY_MERCHANT_KEY=
FINPAY_MERCHANT_SECRET=
FINPAY_REDIRECT_URL=
FINPAY_CALLBACK_URL=
FINPAY_PAYMENT_ENDPOINT=/payment
FINPAY_STATUS_ENDPOINT=/status
FINPAY_TIMEOUT=30
```

### Simulasi callback (development)

Saat kredensial FinPay belum ada, alur bisa disimulasikan dengan POST ke
`/finpay/callback` (route ini sudah dikecualikan dari CSRF):

```bash
curl -X POST http://localhost:8000/finpay/callback \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1, "status": "paid", "payment_reference": "SIM-1"}'
```

Callback sukses akan mengubah order jadi `paid`, membuat `entitlements`
(`download_expires_at` = +3 bulan), dan mengirim email notifikasi.

---

## Alur Download & Watermark PDF

- `app/Services/PdfWatermarkService.php` — membubuhkan watermark teks
  (`Nama Usaha - email`) pada setiap halaman PDF (FPDI + FPDF).
- `app/Http/Controllers/DownloadController.php`:
  - `GET /courses/{course}/download` (login, butuh entitlement aktif).
  - `GET /guest/download/{order}/{course}` (guest, signed URL via email).
- `Entitlement::downloadUrl()` — helper untuk menghasilkan URL download.
- Setiap download dicatat ke `download_logs`. File watermark sementara dihapus
  otomatis setelah terkirim (`deleteFileAfterSend`).

Tombol download hanya tampil bila `download_expires_at > now()`.

---

## Halaman & UI

Blade + Tailwind CSS v4 + Alpine.js (CDN), font Plus Jakarta Sans, mobile-first.

- **Katalog** (`/`) — grid card, filter prodi/semester, pencarian real-time (Alpine.js).
- **Detail course** (`/courses/{course}`) — judul, deskripsi, harga, tombol Beli/Download.
- **Checkout** (`/checkout`) — form guest (nama/email/WA) + ringkasan pesanan.
- **Sukses** (`/payment/success`) — instruksi / tombol download bila paid.
- **Dashboard user** (`/dashboard`) — riwayat order & daftar matkul + status download.
- **Panel admin** (`/admin`) — CRUD course, upload PDF, kelola order, konfirmasi manual, reset download.
- **Halaman statis** — Syarat & Ketentuan, Kebijakan Refund, Kontak.
- **Tombol WhatsApp** mengambang.

### Build asset

```bash
# pakai Node 22 (Laragon)
$env:PATH = 'C:\laragon\bin\nodejs\node-v22.22.1-win-x64;' + $env:PATH
npm install
npm run build
```

---

## Testing

```bash
php artisan test
```

Feature test mencakup:

- Checkout membuat order pending.
- Callback FinPay sukses membuat entitlement & mengubah status order.
- User tanpa entitlement tidak bisa download.
- Download setelah 3 bulan ditolak (`Carbon::setTestNow`).
- Batas maksimal 8 mata kuliah per akun ditolak saat checkout ke-9.

> Test memakai **SQLite in-memory**. Pastikan ekstensi `pdo_sqlite` aktif di `php.ini`
> (pada setup ini sudah diaktifkan: `extension=pdo_sqlite`).

