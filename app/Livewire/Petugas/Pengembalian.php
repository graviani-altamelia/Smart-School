<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use Livewire\WithPagination;

class Pengembalian extends Component
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
            ->where('status_peminjaman', 'kembali')
            ->whereHas('user', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest('tgl_kembali_asli') // Urutkan berdasarkan waktu kembali terbaru
            ->paginate(10);

        return view('livewire.petugas.pengembalian', [
            'riwayat' => $data
        ])->layout('layouts.app');
    }
}