<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use App\Models\Buku;

class Dashboard extends Component
{
    public function setujui($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $buku = Buku::find($pinjam->buku_id);

        // 1. Cek apakah buku masih ada (mencegah error jika buku dihapus admin)
        if (!$buku) {
            session()->flash('error', 'Gagal! Data buku sudah tidak ada di database.');
            return;
        }

        // 2. Cek apakah stok mencukupi sebelum dikurangi
        if ($buku->jumlah < $pinjam->jumlah_pinjam) {
            session()->flash('error', 'Gagal! Stok buku "' . $buku->judul . '" tidak mencukupi.');
            return;
        }

        // 3. Update status peminjaman
        $pinjam->update(['status_peminjaman' => 'dipinjam']);

        // 4. Kurangi stok buku
        $buku->decrement('jumlah', $pinjam->jumlah_pinjam);

        session()->flash('message', 'Peminjaman berhasil divalidasi!');
    }

    public function tolak($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $pinjam->update(['status_peminjaman' => 'ditolak']);

        session()->flash('error', 'Permintaan peminjaman berhasil ditolak.');
    }

    public function render()
    {
        return view('livewire.petugas.dashboard', [
            // Menggunakan with agar tidak terjadi N+1 query (lebih cepat)
            'permintaan_pending' => Pinjam::with(['user', 'buku'])
                                    ->where('status_peminjaman', 'pending')
                                    ->latest()
                                    ->get(),
            'total_pinjam' => Pinjam::where('status_peminjaman', 'dipinjam')->count(),
        ])->layout('layouts.app');
    }
}