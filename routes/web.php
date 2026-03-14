<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// 1. Landing Page + Auto-Redirect
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'siswa'   => redirect()->route('siswa.dashboard'),
            default   => view('welcome'),
        };
    }
    return view('welcome');
})->name('home');

// 2. Auth: Login & Register (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

// 3. Logout
Route::any('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// 4. Group Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::group([], function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/siswa', \App\Livewire\Admin\Siswa::class)->name('admin.siswa');
        Route::get('/buku', \App\Livewire\Admin\Buku::class)->name('admin.buku');
        Route::get('/kategori', \App\Livewire\Admin\Kategori::class)->name('admin.kategori');
        Route::get('/pinjam', \App\Livewire\Admin\Pinjam::class)->name('admin.pinjam');
        Route::get('/pengembalian', \App\Livewire\Admin\Pengembalian::class)->name('admin.pengembalian');
        Route::get('/log', \App\Livewire\Admin\LogAktivitas::class)->name('admin.log');
    });
});

// 5. Group Petugas
Route::middleware(['auth'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', \App\Livewire\Petugas\Dashboard::class)->name('petugas.dashboard');
    Route::get('/buku', \App\Livewire\Admin\Buku::class)->name('petugas.buku');
    Route::get('/peminjaman', \App\Livewire\Petugas\Peminjaman::class)->name('petugas.peminjaman');
    Route::get('/pengembalian', \App\Livewire\Admin\Pengembalian::class)->name('petugas.pengembalian');
});

// 6. Group Siswa
Route::middleware(['auth'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', \App\Livewire\Siswa\Dashboard::class)->name('siswa.dashboard');
    Route::get('/riwayat', \App\Livewire\Siswa\RiwayatPinjam::class)->name('siswa.riwayat-pinjam');
    Route::get('/buku', \App\Livewire\Siswa\DaftarBuku::class)->name('siswa.daftar-buku');
});

// 7. Laporan
Route::get('/laporan/peminjaman', [\App\Http\Controllers\LaporanController::class, 'cetakPeminjaman'])
    ->name('laporan.peminjaman')
    ->middleware(['auth']);