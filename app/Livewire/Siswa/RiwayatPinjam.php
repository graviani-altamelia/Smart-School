<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Pinjam;

class RiwayatPinjam extends Component
{
    public function render()
    {
        return view('livewire.siswa.riwayat-pinjam', [
            'riwayat' => Pinjam::with('buku')
                ->where('user_id', auth()->id()) 
                ->latest()
                ->get()
        ])->layout('layouts.app');
    }
}