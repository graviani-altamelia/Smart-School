<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam;
use Illuminate\Support\Facades\Auth;

class PinjamBuku extends Component
{
    public $buku_id;
    public $tgl_pinjam;
    public $tgl_kembali;

    public function mount()
    {
        $this->tgl_pinjam = now()->format('Y-m-d');
        $this->tgl_kembali = now()->addDays(7)->format('Y-m-d');
    }

    public function pinjam()
    {
        $this->validate([
            'buku_id' => 'required',
            'tgl_kembali' => 'required|after_or_equal:tgl_pinjam',
        ]);

        $buku = Buku::find($this->buku_id);

        if ($buku && $buku->jumlah > 0) {
            Pinjam::create([
                'user_id' => Auth::id(),
                'buku_id' => $this->buku_id,
                'tgl_pinjam' => $this->tgl_pinjam,
                'tgl_kembali' => $this->tgl_kembali,
                'status' => 'dipinjam'
            ]);

            $buku->decrement('jumlah');
            
            $this->reset('buku_id');
            session()->flash('message', 'Peminjaman buku ' . $buku->judul . ' berhasil diajukan!');
        } else {
            session()->flash('error', 'Stok buku habis!');
        }
    }

    public function render()
    {
        return view('livewire.siswa.pinjam-buku', [
            'bukus' => Buku::where('jumlah', '>', 0)->get() 
        ])->layout('layouts.app');
    }
}