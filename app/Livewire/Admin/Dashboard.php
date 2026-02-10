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
        // data Statistik
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalBuku  = Buku::sum('jumlah'); 
        $totalPinjam = Pinjam::where('status', 'dipinjam')->count();
        $terlambat   = Pinjam::where('status', 'dipinjam')
                        ->where('tgl_kembali', '<', now())
                        ->count();

        // data Aktivitas Terbaru
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