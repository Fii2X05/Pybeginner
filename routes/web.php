<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

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

// Modul & Pelajaran serta Latihan (Memerlukan Login)
Route::middleware('auth')->group(function () {
    Route::get('/modul', [ModulController::class, 'index'])->name('modul.index');
    Route::get('/modul/mulai', function () {
        return redirect()->route('modul.index');
    })->name('modul.mulai');

    Route::get('/modul/01/dasar-python', [ModulController::class, 'detail01'])->name('modul.detail.01');
    Route::get('/modul/02/variabel-tipe-data', [ModulController::class, 'detail02'])->name('modul.detail.02');
    Route::get('/modul/03/percabangan-logika', [ModulController::class, 'detail03'])->name('modul.detail.03');
    Route::get('/modul/04/perulangan-loops', [ModulController::class, 'detail04'])->name('modul.detail.04');
    Route::get('/modul/05/fungsi-modularitas', [ModulController::class, 'detail05'])->name('modul.detail.05');
    Route::get('/modul/06/struktur-data', [ModulController::class, 'detail06'])->name('modul.detail.06');
    Route::get('/modul/07/pengantar-pandas-numpy', [ModulController::class, 'detail07'])->name('modul.detail.07');

    Route::post('/modul/{nomor}/toggle-selesai', [ModulController::class, 'toggleComplete'])
        ->name('modul.toggle-complete');

    Route::get('/modul/{course:slug}/{module:slug}/{lesson:slug}', [ModulController::class, 'show'])
        ->scopeBindings()->name('lesson.show');

    Route::post('/modul/{course:slug}/{module:slug}/{lesson:slug}/selesai', [ModulController::class, 'complete'])
        ->scopeBindings()->name('lesson.complete');

    Route::get('/profile', function () {
        return 'Halaman Profile (belum dibuat)';
    })->name('profile');

    Route::get('/riwayat-submission', [SubmissionController::class, 'history'])->name('submissions.history');
    Route::post('/submissions', [SubmissionController::class, 'submit'])->name('submissions.submit');
    Route::post('/run', [SubmissionController::class, 'run'])->name('submissions.run');
    Route::post('/execute', [SubmissionController::class, 'execute'])->name('submissions.execute');

    Route::post('/exercises/{exerciseId}/test-cases/{testCaseId}/run', [SubmissionController::class, 'runTestCase'])
        ->name('submissions.test-case.run');
    Route::post('/exercises/{exerciseId}/test-cases/progress', [SubmissionController::class, 'testCaseProgress'])
        ->name('submissions.test-case.progress');

    // --- Exercise & Code Editor ---
    Route::get('/latihan', [ExerciseController::class, 'index'])->name('latihan.index');
    Route::get('/latihan/{slug}', [ExerciseController::class, 'show'])->name('latihan.show');

    // Route lama /playground dipertahankan sebagai redirect supaya link lama di view
    // teman tidak error "Route [playground] not defined". Hapus kalau sudah tidak dipakai.
    Route::get('/playground', function () {
        return redirect()->route('latihan.index');
    })->name('playground');
});
