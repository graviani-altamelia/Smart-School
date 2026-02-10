<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LogAktivitas as LogModel;
use Livewire\WithPagination;

class LogAktivitas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.admin.log-aktivitas', [
            'logs' => LogModel::with('user')->latest()->paginate(15)
        ])->layout('layouts.app');
    }
}