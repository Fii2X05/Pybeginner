<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('beranda');

Route::get('/modul', function () {
    return view('modul');
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

Route::get('/login', function () {
    return 'Halaman Login (belum dibuat)';
})->name('login');

Route::get('/register', function () {
    return 'Halaman Register (belum dibuat)';
})->name('register');

Route::get('/profile', function () {
    return 'Halaman Profile (belum dibuat)';
})->name('profile');
