<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam; 
use Illuminate\Support\Facades\Auth;

class HomeComponent extends Component
{
    public function render()
    {
        $buku = Buku::where('jumlah', '>', 0)->latest()->take(4)->get();

        $jumlah_pinjam_aktif = Pinjam::where('user_id', Auth::id())
                                     ->where('status', 'dipinjam')
                                     ->count();

        return view('livewire.home-component', [
            'buku' => $buku,
            'jumlah_pinjam_aktif' => $jumlah_pinjam_aktif 
        ])->layout('layouts.app');
    }

    public function pinjamBuku($id)
    {
        if (!Auth::check()) {
            return session()->flash('error', 'Kamu harus login terlebih dahulu.');
        }

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