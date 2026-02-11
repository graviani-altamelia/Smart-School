<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\Pinjam;
use Illuminate\Support\Facades\Auth;

class PinjamBuku extends Component
{
    use WithPagination;

    public $buku_id; 
    public $tgl_pinjam;
    public $tgl_kembali;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tgl_pinjam = now()->format('Y-m-d');
        $this->tgl_kembali = now()->addDays(7)->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function tampilkanForm($id)
    {
        $this->buku_id = $id;
    }

    public function batal()
    {
        $this->reset('buku_id');
    }

    public function pinjam()
    {
        $this->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tgl_kembali' => 'required|after:tgl_pinjam',
        ]);

        $buku = Buku::find($this->buku_id);

        // Proteksi: Cek tunggakan
        $adaTunggakan = Pinjam::where('user_id', Auth::id())
            ->where('status_peminjaman', 'dipinjam')
            ->where('tgl_kembali', '<', now()->format('Y-m-d'))
            ->exists();

        if ($adaTunggakan) {
            session()->flash('error', 'Kembalikan dulu buku yang telat sebelum pinjam lagi ya!');
            return;
        }

        if ($buku && $buku->jumlah > 0) {
            Pinjam::create([
                'user_id'           => Auth::id(),
                'buku_id'           => $this->buku_id,
                'judul'             => $buku->judul,
                'tgl_pinjam'        => $this->tgl_pinjam,
                'tgl_kembali'       => $this->tgl_kembali,
                'jumlah_pinjam'     => 1,
                'status_peminjaman' => 'dipinjam',
                'denda'             => 0,
            ]);

            $buku->decrement('jumlah');
            session()->flash('message', 'Berhasil! Cek status di Riwayat Saya.');
            $this->reset('buku_id');
        }
    }

    public function render()
    {
        $stat_pinjam = Pinjam::where('user_id', Auth::id())
            ->where('status_peminjaman', 'dipinjam')
            ->count();

        $stat_denda = Pinjam::where('user_id', Auth::id())
            ->sum('denda');

        $query = Buku::query();
        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
        }

        return view('livewire.siswa.pinjam-buku', [
            'bukus' => $query->latest()->paginate(12),
            'bukuTerpilih' => $this->buku_id ? Buku::find($this->buku_id) : null,
            'stat_pinjam' => $stat_pinjam,
            'stat_denda' => $stat_denda
        ])->layout('layouts.app');
    }
}