<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Pinjam;
use Illuminate\Support\Facades\Auth;

class RiwayatPinjam extends Component
{
    public function render()
    {
        return view('livewire.siswa.riwayat-pinjam', [
            'riwayat' => Pinjam::with('buku')
                ->where('user_id', Auth::id()) 
                ->latest()
                ->get()
        ])->layout('layouts.app');
    }
}