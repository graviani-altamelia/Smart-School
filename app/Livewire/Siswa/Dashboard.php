<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $selected_buku; 

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function pilihBuku($id)
    {
        $this->selected_buku = Buku::find($id);
        $this->dispatch('show-modal');
    }

    public function pinjamBuku()
    {
        if (!$this->selected_buku || $this->selected_buku->jumlah <= 0) {
            return;
        }

        Pinjam::create([
            'user_id' => Auth::id(),
            'buku_id' => $this->selected_buku->id,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7), 
            'status' => 'dipinjam'
        ]);

        $this->selected_buku->decrement('jumlah'); 
        
        $this->dispatch('hide-modal'); 
        session()->flash('message', 'Buku berhasil dipinjam!');
    }

    public function render()
    {
        $bukus = Buku::where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('penulis', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(8);

        return view('livewire.siswa.dashboard', [
            'bukus' => $bukus
        ])->layout('layouts.app');
    }
}