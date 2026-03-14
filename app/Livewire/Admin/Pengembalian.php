<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pinjam;
use App\Models\Pengembalian as ModelPengembalian;
use App\Models\LogAktivitas as LogModel;
use App\Models\Buku;
use Carbon\Carbon;

class Pengembalian extends Component
{
    use WithPagination;
    
    public $search = ''; 
    protected $paginationTheme = 'bootstrap';
    public $selected_id, $tgl_kembali, $jumlah_pinjam;
    public $isEdit = false;

    public function updatingSearch() { $this->resetPage(); }

    public function kembalikan($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        
        // Ambil nominal denda dari fungsi di model (SINKRON!)
        $nominalDenda = $pinjam->hitungDenda();

        // 1. Simpan ke riwayat pengembalian
        ModelPengembalian::create([
            'pinjam_id' => $pinjam->id,
            'tgl_kembali' => Carbon::now('Asia/Jakarta')->toDateString(),
            'denda' => $nominalDenda,
        ]);

        // 2. Update status Pinjam
        $pinjam->update([
            'status_peminjaman' => 'dikembalikan',
            'tgl_kembali_asli' => Carbon::now('Asia/Jakarta'),
            'denda' => $nominalDenda 
        ]);

        // 3. Tambah stok buku
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) { 
            $buku->increment('jumlah', $pinjam->jumlah_pinjam); 
        }

        LogModel::add('Kembali', 'Pengembalian', 'Buku ' . $pinjam->judul . ' kembali. Denda: Rp ' . number_format($nominalDenda));
        session()->flash('message', 'Buku berhasil dikembalikan!');
    }

    public function update()
    {
        $this->validate([
            'tgl_kembali' => 'required|date',
            'jumlah_pinjam' => 'required|integer|min:1'
        ]);

        $pinjam = Pinjam::findOrFail($this->selected_id);
        $pinjam->update([
            'tgl_kembali' => $this->tgl_kembali,
            'jumlah_pinjam' => $this->jumlah_pinjam
        ]);

        $this->isEdit = false;
        session()->flash('message', 'Data berhasil diperbarui!');
    }

    public function render()
    {
        $loans = Pinjam::with(['user', 'buku'])
            ->whereIn('status_peminjaman', ['dipinjam', 'terlambat'])
            ->where(function($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()->paginate(10);

        return view('livewire.admin.pengembalian', ['loans' => $loans])->layout('layouts.app');
    }
}