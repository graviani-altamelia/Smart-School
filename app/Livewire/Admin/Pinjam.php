<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pinjam as PinjamModel; 
use App\Models\Buku;

class Pinjam extends Component
{
    // Fungsi untuk memproses pengembalian buku
    public function kembalikanBuku($id)
    {
        $pinjam = PinjamModel::find($id);

        if ($pinjam && $pinjam->status_peminjaman == 'dipinjam') {
            // 1. Update status peminjaman
            $pinjam->update([
                'status_peminjaman' => 'dikembalikan',
                'tgl_kembali' => now() // Mencatat tanggal asli pengembalian
            ]);

            // 2. Tambah kembali stok buku
            $buku = Buku::find($pinjam->buku_id);
            if ($buku) {
                $buku->increment('jumlah');
            }

            session()->flash('message', 'Buku berhasil dikembalikan!');
        }
    }

    // Fungsi untuk menghapus riwayat (jika diperlukan)
    public function hapusRiwayat($id)
    {
        PinjamModel::destroy($id);
        session()->flash('message', 'Riwayat peminjaman dihapus.');
    }

    public function render()
    {
        // Mengambil data dengan relasi user dan buku
        $dataPinjam = PinjamModel::with(['user', 'buku'])->latest()->get();

        return view('livewire.admin.pinjam', [
            'peminjamans' => $dataPinjam
        ])->layout('layouts.app');
    }
}