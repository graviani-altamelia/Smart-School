<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pinjam;
use App\Models\Buku;
use App\Models\LogAktivitas; 
use Carbon\Carbon;

class Pengembalian extends Component
{
    public function render()
    {
        $loans = Pinjam::with(['user', 'buku'])
                        ->where('status_peminjaman', 'Dipinjam')
                        ->latest()
                        ->get()
                        ->map(function($loan) {
                            $tenggat = Carbon::parse($loan->tgl_kembali);
                            $hari_ini = Carbon::now();
                            
                            $loan->terlambat = 0;
                            $loan->denda = 0;

                            if ($hari_ini > $tenggat) {
                                $loan->terlambat = $hari_ini->diffInDays($tenggat);
                                $loan->denda = $loan->terlambat * 1000; 
                            }
                            return $loan;
                        });

        return view('livewire.admin.pengembalian', [
            'loans' => $loans
        ])->layout('layouts.app');
    }

    public function kembalikan($id)
    {
        $pinjam = Pinjam::with(['user', 'buku'])->findOrFail($id);
        
        $pinjam->update([
            'status_peminjaman' => 'Kembali',
            'tgl_kembali' => now(), 
        ]);

        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('jumlah', $pinjam->jumlah_pinjam);
        }

        LogAktivitas::add(
            'Kembali', 
            'Peminjaman', 
            'Menerima pengembalian buku: ' . ($pinjam->buku->judul ?? 'Buku Dihapus') . 
            ' dari siswa: ' . ($pinjam->user->name ?? 'Siswa Dihapus')
        );

        session()->flash('message', 'Buku berhasil dikembalikan!');
    }
}