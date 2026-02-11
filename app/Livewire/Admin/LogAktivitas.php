<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogAktivitas as LogModel;

class LogAktivitas extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $all_logs = LogModel::with('user')->latest()->paginate(15);

        return view('livewire.admin.log-aktivitas', [
            'logs' => $all_logs
        ])->layout('layouts.app');
    }
}