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

// Modul & Pelajaran
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

// Latihan, Submission & Editor
Route::get('/profile', function () {
    return 'Halaman Profile (belum dibuat)';
})->name('profile');

Route::get('/riwayat-submission', [SubmissionController::class, 'history'])->name('submissions.history');
Route::post('/submissions', [SubmissionController::class, 'submit'])->name('submissions.submit');
Route::post('/run', [SubmissionController::class, 'run'])->name('submissions.run');

Route::post('/exercises/{exerciseId}/test-cases/{testCaseId}/run', [SubmissionController::class, 'runTestCase'])
    ->name('submissions.test-case.run');
Route::post('/exercises/{exerciseId}/test-cases/progress', [SubmissionController::class, 'testCaseProgress'])
    ->name('submissions.test-case.progress');

Route::get('/latihan', [ExerciseController::class, 'index'])->name('latihan.index');
Route::get('/latihan/{slug}', [ExerciseController::class, 'show'])->name('latihan.show');

// Redirect playground lama
Route::get('/playground', function () {
    return redirect()->route('latihan.index');
})->name('playground');

// Panel Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/materi', [AdminController::class, 'materi'])->name('materi');
    Route::post('/materi', [AdminController::class, 'storeModule'])->name('materi.store');
    Route::put('/materi/{module}', [AdminController::class, 'updateModule'])->name('materi.update');
});
