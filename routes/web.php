<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Auth
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login')->middleware('guest');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/siswa', \App\Livewire\Admin\Siswa::class)->name('admin.siswa');
    Route::get('/buku', \App\Livewire\Admin\Buku::class)->name('admin.buku');
    Route::get('/kategori', \App\Livewire\Admin\Kategori::class)->name('admin.kategori');
    Route::get('/pinjam', \App\Livewire\Admin\Pinjam::class)->name('admin.pinjam');
    Route::get('/pengembalian', \App\Livewire\Admin\Pengembalian::class)->name('admin.pengembalian');
    Route::get('/log', \App\Livewire\Admin\LogAktivitas::class)->name('admin.log');
});

// siswa
Route::middleware(['auth'])->group(function () {
    Route::get('/', \App\Livewire\HomeComponent::class)->name('home');
    Route::get('/siswa/dashboard', \App\Livewire\Siswa\PinjamBuku::class)->name('siswa.dashboard');
    
    Route::get('/siswa/pinjam', \App\Livewire\Siswa\PinjamBuku::class)->name('siswa.pinjam-buku');
    Route::get('/siswa/riwayat', \App\Livewire\Siswa\RiwayatPinjam::class)->name('siswa.riwayat-pinjam');
    Route::get('/siswa/buku', \App\Livewire\Siswa\DaftarBuku::class)->name('siswa.daftar-buku');
});