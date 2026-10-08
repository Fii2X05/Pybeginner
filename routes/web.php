<?php

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

Route::get('/modul', function () {
    return view('modul');
    // Nanti kalau sudah ada data modul dari database, bisa diganti jadi:
    // $modulList = Modul::all();
    // return view('modul', compact('modulList'));
})->name('modul.index');

Route::get('/modul/mulai', function () {
    return redirect()->route('modul.index');
})->name('modul.mulai');

Route::get('/latihan', function () {
    return view('latihan');
})->name('latihan.index');

Route::get('/playground', function () {
    return view('playground');
})->name('playground');

Route::get('/profile', function () {
    return 'Halaman Profile (belum dibuat)';
})->name('profile');
