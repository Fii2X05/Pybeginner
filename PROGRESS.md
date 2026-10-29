# Status PyLearn

Snapshot pekerjaan per 29 Oktober 2026. Branch lokal saat pemeriksaan berada di `main` pada commit `4dcd4db`; kesesuaian dengan upstream belum diperiksa pada sesi ini.

## Ringkasan

Fitur inti MVP sudah tersedia dan validasi otomatis yang sebelumnya gagal kini lulus. Perbaikan juga menutup beberapa kontrol UI yang sebelumnya tampak aktif tetapi belum bekerja. Integrasi grading sudah diuji terhadap Judge0 lokal yang berjalan.

## Pekerjaan yang diselesaikan

### P0 — Tes dan alur admin

- Update latihan mempertahankan status publikasi saat request edit tidak mengirim `is_published`.
- Halaman uji kunci jawaban sekarang menampilkan editor, hasil per test case, keluaran, error, waktu, dan status case tersembunyi.
- Label submission non-lolos di panel admin dilokalkan menjadi `Belum Lolos`; label daftar test case konsisten.
- Tes guest diselaraskan dengan proteksi autentikasi yang berlaku. Tes tambahan memastikan guest tidak dapat menjalankan kode atau membuat submission.

### P1 — Penilaian dan Judge0

- Kegagalan Judge0 yang tidak membawa stderr/diagnostik kini tetap menampilkan deskripsi status, termasuk `Time Limit Exceeded`.
- Tes mencakup hidden test case, runtime error, timeout, jawaban salah, dan layanan Judge0 yang tidak tersedia; kegagalan layanan saat submit menghasilkan error 502 dan tidak menyimpan submission.
- Tes integrasi opsional menjalankan alur autentikasi test harness → uji test case → submission nyata → progres materi → riwayat.
- Smoke test ke Judge0 lokal berhasil: API `/about` dan `/languages` merespons 200; language ID 10 adalah Python 3.7.7; program aman menghasilkan `42`.

### P2 — Riwayat dan kontrol tampilan

- Pencarian riwayat berdasarkan judul/slug atau nomor submission, filter hasil, dan filter modul memakai data database; paginasi mempertahankan filter.
- Kartu statistik dan jumlah submission tujuh hari terakhir bersumber dari data aktual, bukan angka contoh.
- Tombol salin kode berfungsi dan menampilkan status berhasil/gagal. Teks feedback, runtime, XP, serta label versi yang sebelumnya statis atau tidak sesuai data dihapus/diganti.
- Tautan `#` pada navigasi/footer modul diganti dengan route yang tersedia; fitur OOP yang belum ada ditandai sebagai belum tersedia.
- Akun E2E learner diselaraskan dengan akun demo yang dibuat seeder.

### P3 — Setup dan data demo

- `DatabaseSeeder` kini membuat materi, akun demo, dan latihan. Pembuatan akun learner tidak lagi bergantung pada ID database tertentu atau sintaks sequence khusus PostgreSQL.
- README menjelaskan setup lokal, SQLite, seeder, akun demo, konfigurasi Judge0/Google OAuth, storage avatar, tes, dan peringatan deployment.
- Migrasi serta seeder berhasil dijalankan dari database SQLite kosong sementara.

## Validasi

- `php artisan test --compact`: **127 tes; 126 lulus, 1 dilewati**. Tes yang dilewati adalah integrasi Judge0 live dan memang opt-in.
- Tes live khusus Laravel → Judge0: **1 lulus, 13 assertion**; mencakup test case, submission, progres, dan riwayat.
- `npm run build`: berhasil. Ada peringatan opsional bahwa `fontaine` belum terpasang; build tidak terpengaruh.
- `git diff --check`: tidak menemukan whitespace error.

## Yang masih perlu dilakukan sebelum rilis

1. Lakukan smoke test UI browser lengkap secara manual, termasuk login melalui puzzle CAPTCHA, membuka materi/latihan, dan memeriksa hasil di halaman riwayat. Tes live terotomasi menguji alur aplikasi dengan user terautentikasi, tetapi tidak menyelesaikan puzzle CAPTCHA di browser.
2. Verifikasi callback Google OAuth menggunakan kredensial lingkungan demo tanpa mencatat atau membagikan secret.
3. Sebelum deployment, tinjau ulang isolasi Judge0: compose lokal memakai worker dengan hak istimewa tinggi dan memublikasikan port API ke semua antarmuka host. Jangan mengekspos konfigurasi demo ke internet.
4. Uji deployment dengan kredensial unik, `APP_DEBUG=false`, database/penyimpanan produksi, serta prosedur backup dan pemulihan.

`erd-pbl.svg` tetap merupakan referensi ERD dan bukan item backlog.
