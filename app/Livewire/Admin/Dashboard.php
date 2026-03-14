<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Buku;
use App\Models\Pinjam;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $hariIni = Carbon::now('Asia/Jakarta')->startOfDay();

        // 1. Total Siswa Tetap Sama
        $totalSiswa = User::where('role', 'siswa')->count();

        // 2. Stok Tersedia (Buku fisik yang ada di perpustakaan)
        $totalBuku = Buku::sum('jumlah'); 

        // 3. PERBAIKAN: Buku Keluar
        // Hanya menghitung jumlah_pinjam dari transaksi yang statusnya 'dipinjam' atau 'terlambat'
        // Status 'pending' tidak dihitung karena buku secara fisik belum keluar dari perpustakaan
        $totalPinjam = Pinjam::whereIn('status_peminjaman', ['dipinjam', 'terlambat'])
                        ->sum('jumlah_pinjam');

        // 4. PERBAIKAN: Melewati Tenggat
        // Menghitung berapa banyak transaksi yang sudah melewati tanggal kembali tapi belum dikembalikan
        $terlambat = Pinjam::whereIn('status_peminjaman', ['dipinjam', 'terlambat'])
                        ->where('tgl_kembali', '<', $hariIni)
                        ->count();

        // 5. Aktivitas Terbaru
        $aktivitas = Pinjam::with(['user', 'buku'])
                        ->latest()
                        ->take(5)
                        ->get();

        return view('livewire.admin.dashboard', [
            'totalSiswa'  => $totalSiswa,
            'totalBuku'   => $totalBuku,
            'totalPinjam' => $totalPinjam,
            'terlambat'   => $terlambat,
            'aktivitas'   => $aktivitas
        ])->layout('layouts.app');
    }
}