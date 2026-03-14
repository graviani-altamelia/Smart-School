<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Carbon\Carbon;

class Pengembalian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $showModal = false;
    
    // Properti Form
    public $pinjam_id, $tgl_kembali_asli, $denda;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $this->pinjam_id = $id;
        // Gunakan format yang dikenali oleh input type="datetime-local"
        $this->tgl_kembali_asli = Carbon::parse($pinjam->tgl_kembali_asli)->format('Y-m-d\TH:i');
        $this->denda = $pinjam->denda;

        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'tgl_kembali_asli' => 'required',
            'denda' => 'required|numeric|min:0',
        ]);

        $pinjam = Pinjam::findOrFail($this->pinjam_id);
        $pinjam->update([
            'tgl_kembali_asli' => $this->tgl_kembali_asli,
            'denda' => $this->denda,
        ]);

        $this->showModal = false;
        session()->flash('message', 'Riwayat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Pinjam::findOrFail($id)->delete();
        session()->flash('message', 'Data riwayat dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['pinjam_id', 'tgl_kembali_asli', 'denda']);
    }

    public function render()
    {
        $data = Pinjam::with(['user', 'buku'])
            ->where('status_peminjaman', 'dikembalikan')
            ->where(function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('judul', 'like', '%' . $this->search . '%');
            })
            ->latest('tgl_kembali_asli')
            ->paginate(10);

        return view('livewire.petugas.pengembalian', [
            'riwayat' => $data,
            'total_denda' => Pinjam::where('status_peminjaman', 'dikembalikan')->sum('denda')
        ])->layout('layouts.app');
    }
}