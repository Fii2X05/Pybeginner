<?php

use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

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
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/bantuan', [SupportController::class, 'create'])->name('support.create');
    Route::post('/bantuan', [SupportController::class, 'store'])->name('support.store');
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

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

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
    Route::get('/materi/tambah', [AdminContentController::class, 'createModule'])->name('materi.create');
    Route::post('/materi', [AdminContentController::class, 'storeModule'])->name('materi.store');
    Route::get('/materi/{module}/edit', [AdminContentController::class, 'editModule'])->name('materi.edit');
    Route::put('/materi/{module}', [AdminContentController::class, 'updateModule'])->name('materi.update');
    Route::delete('/materi/{module}', [AdminContentController::class, 'destroyModule'])->name('materi.destroy');
    Route::get('/materi/{module}/pelajaran', [AdminContentController::class, 'moduleLessons'])->name('materi.lessons');
    Route::post('/materi/{module}/pelajaran', [AdminContentController::class, 'storeLesson'])->name('materi.lessons.store');
    Route::get('/pelajaran/{lesson}/edit', [AdminContentController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/pelajaran/{lesson}', [AdminContentController::class, 'updateLesson'])->name('lessons.update');
    Route::put('/pelajaran/{lesson}/status', [AdminContentController::class, 'updateLessonStatus'])->name('lessons.status.update');
    Route::delete('/pelajaran/{lesson}', [AdminContentController::class, 'destroyLesson'])->name('lessons.destroy');

    Route::get('/latihan', [AdminController::class, 'latihan'])->name('latihan');
    Route::get('/latihan/tambah', [AdminContentController::class, 'createExercise'])->name('latihan.create');
    Route::post('/latihan', [AdminContentController::class, 'storeExercise'])->name('latihan.store');
    Route::get('/latihan/{exercise}/edit', [AdminContentController::class, 'editExercise'])->name('latihan.edit');
    Route::put('/latihan/{exercise}', [AdminContentController::class, 'updateExercise'])->name('latihan.update');
    Route::put('/latihan/{exercise}/status', [AdminContentController::class, 'updateExerciseStatus'])->name('latihan.status.update');
    Route::delete('/latihan/{exercise}', [AdminContentController::class, 'destroyExercise'])->name('latihan.destroy');
    Route::get('/latihan/{exercise}/test-case', [AdminContentController::class, 'testCases'])->name('latihan.test-cases');
    Route::get('/latihan/{exercise}/test-case/tambah', [AdminContentController::class, 'createTestCase'])->name('latihan.test-cases.create');
    Route::post('/latihan/{exercise}/test-case', [AdminContentController::class, 'storeTestCase'])->name('latihan.test-cases.store');
    Route::get('/test-case/{testCase}/edit', [AdminContentController::class, 'editTestCase'])->name('test-cases.edit');
    Route::put('/test-case/{testCase}', [AdminContentController::class, 'updateTestCase'])->name('test-cases.update');
    Route::delete('/test-case/{testCase}', [AdminContentController::class, 'destroyTestCase'])->name('test-cases.destroy');
    Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
    Route::get('/siswa/{user}', [AdminController::class, 'siswaShow'])->name('siswa.show');
    Route::put('/siswa/{user}/role', [AdminController::class, 'updateStudentRole'])->name('siswa.role');
    Route::post('/siswa/{user}/suspension', [AdminController::class, 'toggleStudentSuspension'])->name('siswa.suspension');
    Route::delete('/siswa/{user}', [AdminController::class, 'deleteStudent'])->name('siswa.destroy');
    Route::get('/penilaian', [AdminController::class, 'penilaian'])->name('penilaian');
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [AdminController::class, 'markNotificationRead'])->name('notifications.read');
    Route::put('/pengaturan/profile', [AdminController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::put('/pengaturan/password', [AdminController::class, 'updatePassword'])->name('pengaturan.password');
});
