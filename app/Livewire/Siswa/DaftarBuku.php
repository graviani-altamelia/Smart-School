<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DaftarBuku extends Component
{
    use WithPagination;

    public $search = ''; 
    protected $paginationTheme = 'bootstrap'; 

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $semuaBuku = Buku::where(function($query) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(12); 
        return view('livewire.siswa.daftar-buku', [
            'bukus' => $semuaBuku
        ])->layout('layouts.app');
    }

    public function pinjamBuku($id)
    {
        $buku = Buku::find($id);
        
        if ($buku && $buku->jumlah > 0) {
            Pinjam::create([
                'user_id' => Auth::id(),
                'buku_id' => $id,
                'tgl_pinjam' => now(),
                'tgl_kembali' => now()->addDays(7), 
                'status' => 'dipinjam'
            ]);

            $buku->decrement('jumlah'); 
            session()->flash('message', 'Buku ' . $buku->judul . ' berhasil dipinjam!');
        }
    }
}