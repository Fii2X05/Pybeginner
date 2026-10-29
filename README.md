# PyLearn CourseWeb

Platform belajar Python berbahasa Indonesia dengan materi dan progres belajar, editor kode, penilaian otomatis Judge0, riwayat submission, serta panel admin.

## Persyaratan

- PHP 8.3 atau lebih baru dan Composer
- Node.js yang didukung Vite 8 (20.19+ atau 22.12+) dan npm
- Docker Desktop dengan Docker Compose untuk menjalankan Judge0 secara lokal

## Menyiapkan aplikasi lokal

Jalankan dari root repository di PowerShell:

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate --no-interaction
if (-not (Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite }
php artisan migrate --seed --no-interaction
php artisan storage:link --no-interaction
npm install
npm run build
```

Seeder membuat materi dan latihan demo beserta akun admin dan learner. Kredensial seed hanya untuk mesin lokal:

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | `admin@pybeginner.com` | `admin123` |
| Learner demo | `learner@pybeginner.test` | `password` |

Ganti kredensial demo sebelum aplikasi dipakai bersama atau dipublikasikan. Seeder tidak membuat ulang latihan jika tabel latihan sudah berisi data.

## Menjalankan Judge0 lokal

Judge0 versi 1.13.0 tersedia di `judge0-v1.13.0`. Jalankan Docker Desktop, lalu dari root repository:

```powershell
docker compose -f judge0-v1.13.0\docker-compose.yml up -d
Invoke-WebRequest http://127.0.0.1:2358/about
```

Konfigurasi aplikasi ada di `.env`:

| Variabel | Kegunaan |
|---|---|
| `JUDGE0_URL` | URL dasar API, default lokal `http://localhost:2358` |
| `JUDGE0_LANGUAGE_ID` | ID bahasa Judge0; default `10` untuk Python 3.7.7 |
| `JUDGE0_TOKEN` | Token API jika server Judge0 mengaktifkan autentikasi |
| `JUDGE0_AUTH_HEADER` | Nama header token, default `X-Auth-Token` |
| `JUDGE0_TIMEOUT` | Batas waktu permintaan ke Judge0 dalam detik |
| `JUDGE0_CPU_TIME_LIMIT`, `JUDGE0_MEMORY_LIMIT` | Batas sumber daya permintaan |
| `JUDGE0_MAX_PROCESSES_AND_OR_THREADS` | Batas proses/thread |
| `JUDGE0_PER_PROCESS_TIME_LIMIT`, `JUDGE0_PER_PROCESS_MEMORY_LIMIT` | Aktifkan batas per proses/thread |

Jangan publikasikan token, file `.env`, atau konfigurasi berisi kata sandi. Compose Judge0 menjalankan worker dengan hak istimewa tinggi dan membuka API pada semua antarmuka host; gunakan hanya pada lingkungan demo yang dikendalikan, jangan mengekspos port `2358` ke internet, dan evaluasi ulang isolasi sebelum menjalankan kode pengguna yang tidak tepercaya.

Hentikan layanan lokal setelah selesai:

```powershell
docker compose -f judge0-v1.13.0\docker-compose.yml down
```

## Menjalankan aplikasi

Di terminal pertama:

```powershell
php artisan serve
```

Di terminal kedua, untuk kompilasi aset selama pengembangan:

```powershell
npm run dev
```

Buka `http://127.0.0.1:8000`. Login dan pendaftaran email tersedia secara lokal. Login Google bersifat opsional; isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI` di `.env`, lalu daftarkan URI callback tersebut pada konfigurasi OAuth Google. Jangan memasukkan kredensial OAuth ke repository.

Database default menggunakan SQLite, sesi menggunakan database, dan avatar disimpan pada disk publik Laravel. Migrasi menyiapkan tabel aplikasi dan sesi; `storage:link` membuat avatar dapat diakses melalui web.

## Pemeriksaan

```powershell
php artisan test --no-interaction
npm run build
```

Tes fitur memakai HTTP fake untuk Judge0 agar hasilnya deterministik. Untuk memverifikasi integrasi nyata, pastikan Judge0 lokal aktif dan jalankan alur login, latihan, uji kode, pengiriman jawaban, riwayat, serta progres di browser. Periksa juga respons saat Judge0 tidak tersedia dan jangan menganggap permintaan grading yang diproses berarti skor 100.

Tes integrasi opsional berikut mengirim satu program Python aman ke Judge0 lokal melalui endpoint grading aplikasi:

```powershell
$env:RUN_JUDGE0_INTEGRATION_TESTS = '1'
php artisan test --compact tests/Feature/SubmissionGradingTest.php --filter=test_live_judge0_grades_a_python_submission
Remove-Item Env:RUN_JUDGE0_INTEGRATION_TESTS
```

## Catatan deployment

Jangan gunakan akun seed atau `APP_DEBUG=true` di deployment. Atur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` HTTPS, kunci aplikasi dan kredensial layanan yang unik, database dan penyimpanan yang sesuai, serta pengamanan akses ke Judge0. Jangan mengekspos API Judge0 secara langsung kepada klien.
