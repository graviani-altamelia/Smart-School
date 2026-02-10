<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pinjam as PinjamModel; 

class Pinjam extends Component
{
    public function render()
    {
        $dataPinjam = PinjamModel::with(['user', 'buku'])->latest()->get();

        return view('livewire.admin.pinjam', [
            'peminjamans' => $dataPinjam
        ])->layout('layouts.app');
    }
}