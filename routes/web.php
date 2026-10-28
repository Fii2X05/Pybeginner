<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PuzzleCaptchaController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/captcha/puzzle/challenge', [PuzzleCaptchaController::class, 'challenge'])->name('captcha.puzzle.challenge');
    Route::post('/captcha/puzzle/verify', [PuzzleCaptchaController::class, 'verify'])->name('captcha.puzzle.verify');

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

    Route::get('/modul/{course:slug}/{module:slug}', [ModulController::class, 'showModule'])
        ->scopeBindings()->name('module.show');
    Route::post('/modul/{course:slug}/{module:slug}/toggle-selesai', [ModulController::class, 'toggleModuleCompletion'])
        ->scopeBindings()->name('module.toggle-complete');

    // URL pelajaran lama diarahkan ke halaman modul lengkap.
    Route::get('/modul/{course:slug}/{module:slug}/{lesson:slug}', [ModulController::class, 'show'])
        ->scopeBindings()->name('lesson.show');

    Route::post('/modul/{course:slug}/{module:slug}/{lesson:slug}/selesai', [ModulController::class, 'complete'])
        ->scopeBindings()->name('lesson.complete');
    Route::post('/pelajaran/{lesson}/progress', [ModulController::class, 'recordLessonReadingProgress'])
        ->name('lesson.progress');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

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

// --- Panel Admin ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/materi', [AdminController::class, 'materi'])->name('materi');
    Route::post('/materi', [AdminController::class, 'storeModule'])->name('materi.store');
    Route::put('/materi/{module}', [AdminController::class, 'updateModule'])->name('materi.update');
    Route::put('/materi/{module}/tingkat-kesulitan', [AdminController::class, 'updateModuleLevel'])->name('materi.level.update');
    Route::delete('/materi/{module}', [AdminController::class, 'destroyModule'])->name('materi.destroy');

    // Pelajaran / Lessons di dalam Modul
    Route::get('/materi/{module}/pelajaran', [AdminController::class, 'moduleLessons'])->name('materi.lessons');
    Route::post('/materi/{module}/pelajaran', [AdminController::class, 'storeLesson'])->name('materi.lessons.store');
    Route::get('/pelajaran/{lesson}/edit', [AdminController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/pelajaran/{lesson}', [AdminController::class, 'updateLesson'])->name('lessons.update');
    Route::put('/pelajaran/{lesson}/status', [AdminController::class, 'updateLessonStatus'])->name('lessons.status.update');
    Route::delete('/pelajaran/{lesson}', [AdminController::class, 'destroyLesson'])->name('lessons.destroy');

    Route::get('/latihan', [AdminController::class, 'latihan'])->name('latihan');
    Route::get('/latihan/tambah', [AdminController::class, 'createExercise'])->name('latihan.create');
    Route::post('/latihan', [AdminController::class, 'storeExercise'])->name('latihan.store');
    Route::get('/latihan/{exercise}/edit', [AdminController::class, 'editExercise'])->name('latihan.edit');
    Route::put('/latihan/{exercise}', [AdminController::class, 'updateExercise'])->name('latihan.update');
    Route::put('/latihan/{exercise}/status', [AdminController::class, 'updateExerciseStatus'])->name('latihan.status.update');
    Route::delete('/latihan/{exercise}', [AdminController::class, 'destroyExercise'])->name('latihan.destroy');

    // Test case milik latihan
    Route::get('/latihan/{exercise}/test-case', [AdminController::class, 'testCases'])->name('latihan.test-cases');
    Route::get('/latihan/{exercise}/test-case/tambah', [AdminController::class, 'createTestCase'])->name('latihan.test-cases.create');
    Route::post('/latihan/{exercise}/test-case', [AdminController::class, 'storeTestCase'])->name('latihan.test-cases.store');
    Route::get('/test-case/{testCase}/edit', [AdminController::class, 'editTestCase'])->name('test-cases.edit');
    Route::put('/test-case/{testCase}', [AdminController::class, 'updateTestCase'])->name('test-cases.update');
    Route::put('/test-case/{testCase}/aktif', [AdminController::class, 'updateTestCaseActive'])->name('test-cases.active.update');
    Route::delete('/test-case/{testCase}', [AdminController::class, 'destroyTestCase'])->name('test-cases.destroy');
    Route::get('/latihan/{exercise}/uji-kunci', [AdminController::class, 'answerKey'])->name('latihan.answer-key');
    Route::post('/latihan/{exercise}/uji-kunci', [AdminController::class, 'runAnswerKey'])->name('latihan.answer-key.run');
    Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
    Route::get('/penilaian', [AdminController::class, 'penilaian'])->name('penilaian');
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
    Route::put('/pengaturan/profile', [AdminController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::put('/pengaturan/password', [AdminController::class, 'updatePassword'])->name('pengaturan.password');
});
