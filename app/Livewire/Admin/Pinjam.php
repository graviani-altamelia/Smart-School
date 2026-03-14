<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pinjam as PinjamModel; 
use App\Models\Buku;
use App\Models\LogAktivitas as LogModel;
use App\Models\Pengembalian as ModelPengembalian;
use Carbon\Carbon;

class Pinjam extends Component
{
    use WithPagination;

    public $id_pinjam, $tgl_kembali, $status_peminjaman;
    public $isEdit = false;
    public $search = ''; 

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch() { $this->resetPage(); }

    public function edit($id)
    {
        $pinjam = PinjamModel::findOrFail($id);
        $this->isEdit = true;
        $this->id_pinjam = $pinjam->id;
        $this->tgl_kembali = Carbon::parse($pinjam->tgl_kembali)->format('Y-m-d');
        $this->status_peminjaman = $pinjam->status_peminjaman;
    }

    public function update()
    {
        $this->validate([
            'tgl_kembali' => 'required|date',
            'status_peminjaman' => 'required'
        ]);

        $pinjam = PinjamModel::find($this->id_pinjam);
        $pinjam->update([
            'tgl_kembali' => $this->tgl_kembali,
            'status_peminjaman' => $this->status_peminjaman
        ]);

        LogModel::add('Edit', 'Peminjaman', 'Update data pinjam ID: ' . $pinjam->id);
        $this->resetInput();
        session()->flash('message', 'Data peminjaman berhasil diupdate!');
    }

    public function kembalikanBuku($id)
    {
        $pinjam = PinjamModel::findOrFail($id);
        
        if ($pinjam->status_peminjaman !== 'dikembalikan') {
            $nominalDenda = $pinjam->hitungDenda();

            // 1. Catat ke tabel pengembalian
            ModelPengembalian::create([
                'pinjam_id' => $pinjam->id,
                'tgl_kembali' => Carbon::now('Asia/Jakarta')->toDateString(),
                'denda' => $nominalDenda,
            ]);

            // 2. Update status Pinjam & Kunci Nilai Denda
            $pinjam->update([
                'status_peminjaman' => 'dikembalikan',
                'tgl_kembali_asli' => Carbon::now('Asia/Jakarta'),
                'denda' => $nominalDenda
            ]);

            // 3. Tambah Stok Buku Kembali ke Rak
            $buku = Buku::find($pinjam->buku_id);
            if ($buku) { 
                $buku->tambahStok($pinjam->jumlah_pinjam); 
            } 
            
            LogModel::add('Kembali', 'Peminjaman', 'Buku dikembalikan: ' . $pinjam->judul);
            session()->flash('message', 'Buku berhasil dikembalikan dan stok diperbarui!');
        }
    }

    public function hapusRiwayat($id)
    {
        $pinjam = PinjamModel::findOrFail($id);
        $buku = Buku::find($pinjam->buku_id);

        // Jika riwayat dihapus padahal buku belum balik, stok harus dikembalikan manual oleh sistem
        if ($pinjam->status_peminjaman !== 'dikembalikan' && $buku) {
            $buku->tambahStok($pinjam->jumlah_pinjam);
        }

        $pinjam->delete();
        LogModel::add('Hapus', 'Peminjaman', 'Menghapus riwayat: ' . $pinjam->judul);
        session()->flash('message', 'Riwayat dihapus dan stok disesuaikan.');
    }

    public function resetInput()
    {
        $this->reset(['id_pinjam', 'tgl_kembali', 'status_peminjaman', 'isEdit']);
    }

    public function render()
    {
        $peminjamans = PinjamModel::with(['user', 'buku'])
            ->where(function($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()->paginate(10);

        return view('livewire.admin.pinjam', ['peminjamans' => $peminjamans])->layout('layouts.app');
    }
}