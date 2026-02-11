<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Carbon\Carbon;

class Peminjaman extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = Pinjam::with(['user', 'buku'])
            ->where('status_peminjaman', 'dipinjam')
            ->where(function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('buku', function($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.petugas.peminjaman', [
            'peminjaman' => $data
        ])->layout('layouts.app');
    }

    public function kembalikan($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $tgl_kembali_seharusnya = Carbon::parse($pinjam->tgl_kembali);
        $tgl_sekarang = now();
        
        $denda = 0;
        
        if ($tgl_sekarang->gt($tgl_kembali_seharusnya)) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_kembali_seharusnya);
            $denda = $selisih_hari * 1000;
        }

        $pinjam->update([
            'status_peminjaman' => 'kembali',
            'tgl_kembali_asli' => $tgl_sekarang,
            'denda' => $denda 
        ]);

        if ($pinjam->buku) {
            $pinjam->buku->increment('stok', $pinjam->jumlah_pinjam);
        }

        if ($denda > 0) {
            session()->flash('message', 'Buku dikembalikan. Siswa terlambat ' . $selisih_hari . ' hari. Denda: Rp ' . number_format($denda, 0, ',', '.'));
        } else {
            session()->flash('message', 'Buku berhasil dikembalikan tepat waktu.');
        }
    }
}