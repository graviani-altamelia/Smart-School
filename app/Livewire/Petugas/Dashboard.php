<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use App\Models\Buku;
use App\Models\LogAktivitas as LogModel;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $id_pinjam, $tgl_kembali, $isEdit = false;

    // VALIDASI SETUJU
    public function setujui($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $buku = Buku::find($pinjam->buku_id);

        if (!$buku || $buku->jumlah < ($pinjam->jumlah_pinjam ?? 1)) {
            session()->flash('error', 'Gagal! Stok buku tidak mencukupi.');
            return;
        }

        $pinjam->update(['status_peminjaman' => 'dipinjam']);
        $buku->decrement('jumlah', $pinjam->jumlah_pinjam ?? 1);

        LogModel::add('Validasi', 'Petugas', Auth::user()->name . ' menyetujui pinjaman: ' . $pinjam->judul);
        session()->flash('message', 'Peminjaman berhasil divalidasi! ✅');
    }

    // VALIDASI TOLAK
    public function tolak($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $judul = $pinjam->judul;
        $pinjam->update(['status_peminjaman' => 'ditolak']);

        LogModel::add('Tolak', 'Petugas', Auth::user()->name . ' menolak pinjaman: ' . $judul);
        session()->flash('error', 'Permintaan peminjaman ditolak.');
    }

    // CRUD: EDIT TENGGAT
    public function edit($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $this->id_pinjam = $id;
        $this->tgl_kembali = $pinjam->tgl_kembali;
        $this->isEdit = true;
    }

    public function update()
    {
        $pinjam = Pinjam::findOrFail($this->id_pinjam);
        $pinjam->update(['tgl_kembali' => $this->tgl_kembali]);

        LogModel::add('Edit', 'Petugas', Auth::user()->name . ' merubah tenggat: ' . $pinjam->judul);
        $this->isEdit = false;
        session()->flash('message', 'Tenggat waktu diperbarui! ✨');
    }

    // CRUD: HAPUS DATA
    public function hapus($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $judul = $pinjam->judul;
        $pinjam->delete();

        LogModel::add('Hapus', 'Petugas', Auth::user()->name . ' menghapus antrean: ' . $judul);
        session()->flash('message', 'Antrean berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.petugas.dashboard', [
            'permintaan_pending' => Pinjam::with(['user', 'buku'])
                                    ->where('status_peminjaman', 'pending')
                                    ->latest()->get(),
        ])->layout('layouts.app');
    }
}