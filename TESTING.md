# Panduan Menjalankan Test

Project ini menggunakan PHPUnit melalui Laravel Artisan. Jalankan semua perintah
berikut dari direktori root repository di PowerShell.

## Persiapan

Pastikan dependensi Composer sudah terpasang:

```powershell
composer install
```

PHPUnit menggunakan SQLite in-memory sesuai konfigurasi di `phpunit.xml`, sehingga
test tidak mengubah database aplikasi lokal. Sebagian besar test yang memakai
Judge0 menggunakan HTTP fake; server Judge0 tidak perlu dijalankan.

## Menjalankan semua test

```powershell
php artisan test --compact
```

Alternatif melalui script Composer:

```powershell
composer test
```

## Menjalankan test berdasarkan suite

Test unit berada di `tests/Unit`:

```powershell
php artisan test --compact --testsuite=Unit
```

Test fitur berada di `tests/Feature`:

```powershell
php artisan test --compact --testsuite=Feature
```

## Menjalankan file atau test tertentu

Contoh menjalankan unit test untuk layanan Judge0:

```powershell
php artisan test --compact tests/Unit/Judge0ServiceTest.php
```

Contoh menjalankan satu test berdasarkan nama method:

```powershell
php artisan test --compact tests/Unit/Judge0ServiceTest.php --filter=test_it_sends_configured_submission_to_judge0
```

Di PowerShell Windows, garis miring `/` pada argumen path di atas dapat digunakan
oleh PHP/Laravel. Jika ingin, path tersebut dapat ditulis dengan garis miring
terbalik, misalnya `tests\Unit\Judge0ServiceTest.php`.

## Melihat dan menyimpan laporan hasil

Hasil dasar ditampilkan langsung di terminal saat perintah test selesai. Jika
ingin menyimpan laporan terstruktur yang bisa dibuka di editor atau dipakai
tool CI, jalankan PHPUnit dengan opsi `--log-junit`:

```powershell
php vendor\phpunit\phpunit\phpunit --log-junit="test-results\phpunit.xml"
```

Perintah tersebut menjalankan seluruh suite dan menulis laporan JUnit XML ke
`test-results\phpunit.xml`. Buka file tersebut di VS Code atau editor lain untuk
memeriksa ringkasan suite, test yang gagal, dan pesan error.

Untuk laporan teks dengan nama test yang lebih mudah dibaca:

```powershell
php vendor\phpunit\phpunit\phpunit --testdox-text="test-results\phpunit-testdox.txt"
```

Jika hanya ingin membaca error saat test gagal, jalankan test biasa tanpa
`--compact` agar output terminal lebih lengkap:

```powershell
php artisan test
```

Laporan PHPUnit di atas berbeda dari log aplikasi Laravel. Log aplikasi, jika
ada, biasanya berada di `storage\logs\laravel.log`; file itu bukan laporan
ringkasan test PHPUnit.

## Integrasi live dengan Judge0 (opsional)

Sebagian besar test grading memakai HTTP fake. Hanya test integrasi berikut yang
mengirim program ke server Judge0 sungguhan. Pastikan Judge0 lokal aktif sebelum
menjalankannya:

```powershell
$env:RUN_JUDGE0_INTEGRATION_TESTS = '1'
php artisan test --compact tests/Feature/SubmissionGradingTest.php --filter=test_live_judge0_grades_a_python_submission
Remove-Item Env:RUN_JUDGE0_INTEGRATION_TESTS
```

Test integrasi ini biasanya dilewati saat menjalankan suite biasa. Jika terjadi
error sebelum perintah terakhir, hapus variabel tersebut secara manual di
PowerShell:

```powershell
Remove-Item Env:RUN_JUDGE0_INTEGRATION_TESTS
```

## Memahami hasil

- `passed`: test berhasil.
- `failed`: perilaku aktual tidak sesuai dengan yang diperiksa test.
- `skipped`: test sengaja tidak dijalankan, misalnya integrasi live Judge0 tanpa
  `RUN_JUDGE0_INTEGRATION_TESTS=1`.

`--compact` hanya mempersingkat tampilan output; opsi ini tidak mengubah test
yang dijalankan.
