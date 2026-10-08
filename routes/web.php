<?php

use App\Http\Controllers\ModulController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\SubmissionController;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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