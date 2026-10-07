<?php

use App\Http\Controllers\ModulController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

// Auth routes (sementara)
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('beranda')->with('status', 'Fitur login belum terhubung ke database.');
})->name('login.store');

Route::get('/register', function () {
    return view('register');
})->name('register');

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
