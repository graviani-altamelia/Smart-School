<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Buku;
use App\Models\Pinjam;

class Dashboard extends Component
{
    public function render()
    {
        $totalSiswa = User::where('role', 'siswa')->count();

        $totalBuku  = Buku::sum('jumlah'); 

        $totalPinjam = Pinjam::where('status_peminjaman', 'dipinjam')->count();

        $terlambat   = Pinjam::where('status_peminjaman', 'dipinjam')
                        ->where('tgl_kembali', '<', now()->startOfDay())
                        ->count();

        $aktivitas = Pinjam::with(['user', 'buku'])->latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'totalSiswa'  => $totalSiswa,
            'totalBuku'   => $totalBuku,
            'totalPinjam' => $totalPinjam,
            'terlambat'   => $terlambat,
            'aktivitas'   => $aktivitas
        ])->layout('layouts.app');
    }
}