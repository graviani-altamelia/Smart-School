<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/', function() {
    if (!Auth::check()) return redirect('/login');
    
    $role = Auth::user()->role;
    if ($role == 'admin') return redirect()->route('admin.dashboard');
    if ($role == 'petugas') return redirect()->route('petugas.dashboard');
    if ($role == 'siswa') return redirect()->route('siswa.dashboard');
    
    Auth::logout();
    return redirect('/login')->with('error', 'Role tidak dikenali');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'admin:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/siswa', \App\Livewire\Admin\Siswa::class)->name('admin.siswa');
    Route::get('/buku', \App\Livewire\Admin\Buku::class)->name('admin.buku');
    Route::get('/kategori', \App\Livewire\Admin\Kategori::class)->name('admin.kategori');
    Route::get('/pinjam', \App\Livewire\Admin\Pinjam::class)->name('admin.pinjam');
    Route::get('/pengembalian', \App\Livewire\Admin\Pengembalian::class)->name('admin.pengembalian');
    Route::get('/log', \App\Livewire\Admin\LogAktivitas::class)->name('admin.log');
});

Route::middleware(['auth', 'petugas:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', \App\Livewire\Petugas\Dashboard::class)->name('petugas.dashboard');
    Route::get('/buku', \App\Livewire\Admin\Buku::class)->name('petugas.buku');
    Route::get('/peminjaman', \App\Livewire\Petugas\Peminjaman::class)->name('petugas.peminjaman');
    Route::get('/pengembalian', \App\Livewire\Admin\Pengembalian::class)->name('petugas.pengembalian');
});

Route::middleware(['auth', 'siswa:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', \App\Livewire\Siswa\PinjamBuku::class)->name('siswa.dashboard');
    Route::get('/riwayat', \App\Livewire\Siswa\RiwayatPinjam::class)->name('siswa.riwayat-pinjam');
    Route::get('/buku', \App\Livewire\Siswa\DaftarBuku::class)->name('siswa.daftar-buku');
});

Route::get('/laporan/peminjaman', [\App\Http\Controllers\LaporanController::class, 'cetakPeminjaman'])
    ->name('laporan.peminjaman')
    ->middleware(['auth']);