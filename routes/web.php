<?php

use App\Http\Controllers\ModulController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\SubmissionController;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

// Auth routes (sementara)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Proses form login (sementara cuma redirect balik, belum ada logic auth sungguhan)
Route::post('/login', function () {
    return redirect()->route('beranda')->with('status', 'Fitur login belum terhubung ke database.');
})->name('login.store');

// Tampilkan halaman register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Proses form register (sementara cuma redirect balik, belum ada logic simpan user)
Route::post('/register', function () {
    return redirect()->route('login')->with('status', 'Fitur daftar belum terhubung ke database.');
})->name('register.store');

// Modul & Pelajaran (Tugas Adelia)
Route::get('/modul', [ModulController::class, 'index'])->name('modul.index');
Route::get('/modul/mulai', function () {
    return redirect()->route('modul.index');
})->name('modul.mulai');

Route::get('/modul/01/dasar-python', function () {
    return view('modul-detail');
})->name('modul.detail.01');

Route::get('/modul/{course:slug}/{module:slug}/{lesson:slug}', [ModulController::class, 'show'])
    ->scopeBindings()->name('lesson.show');

Route::post('/modul/{course:slug}/{module:slug}/{lesson:slug}/selesai', [ModulController::class, 'complete'])
    ->scopeBindings()->middleware('auth')->name('lesson.complete');

// Latihan & Playground (Tugas Dinda)
Route::get('/latihan', function () {
    return view('latihan');
})->name('latihan.index');

Route::get('/playground', function () {
    return view('playground');
})->name('playground');

Route::get('/profile', function () {
    return 'Halaman Profile (belum dibuat)';
})->name('profile');

Route::get('/riwayat-submission', [SubmissionController::class, 'history'])->name('submissions.history');
Route::post('/submissions', [SubmissionController::class, 'submit'])->name('submissions.submit');
Route::post('/run', [SubmissionController::class, 'run'])->name('submissions.run');
// --- Exercise & Code Editor (Dinda) ---
Route::get('/latihan', [ExerciseController::class, 'index'])->name('latihan.index');
Route::get('/latihan/{slug}', [ExerciseController::class, 'show'])->name('latihan.show');

// Route lama /playground dipertahankan sebagai redirect supaya link lama di view
// teman tidak error "Route [playground] not defined". Hapus kalau sudah tidak dipakai.
Route::get('/playground', function () {
    return redirect()->route('latihan.index');
})->name('playground');