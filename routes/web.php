<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionController;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

Route::get('/login', function () {
    return view('login');
})->name('login');
 
// Proses form login (sementara cuma redirect balik, belum ada logic auth sungguhan)
Route::post('/login', function () {
    // TODO: tambahkan logic autentikasi di sini (cek email & password ke database)
    return redirect()->route('beranda')->with('status', 'Fitur login belum terhubung ke database.');
})->name('login.store');
 
// Tampilkan halaman register
Route::get('/register', function () {
    return view('register');
})->name('register');
 
// Proses form register (sementara cuma redirect balik, belum ada logic simpan user)
Route::post('/register', function () {
    // TODO: tambahkan logic simpan user baru ke database di sini
    return redirect()->route('login')->with('status', 'Fitur daftar belum terhubung ke database.');
})->name('register.store');

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

Route::get('/riwayat-submission', [SubmissionController::class, 'history'])->name('submissions.history');
Route::post('/submissions', [SubmissionController::class, 'submit'])->name('submissions.submit');