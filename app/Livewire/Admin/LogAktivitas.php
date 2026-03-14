<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogAktivitas as LogModel;

class LogAktivitas extends Component
{
    use WithPagination;
    
    public $search = ''; // Tambahkan properti search
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query search yang mencari di aksi, kategori, deskripsi, atau nama user
        $all_logs = LogModel::with('user')
            ->where(function($query) {
                $query->where('aksi', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.log-aktivitas', [
            'logs' => $all_logs
        ])->layout('layouts.app');
    }
}