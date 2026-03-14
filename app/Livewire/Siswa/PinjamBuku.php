<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\Pinjam;
use App\Models\LogAktivitas as LogModel;
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
        $this->tgl_kembali = now()->addDay()->format('Y-m-d'); 
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function tampilkanForm($id)
    {
        $this->buku_id = $id;
        $this->tgl_kembali = now()->addDay()->format('Y-m-d');
    }

    public function batal()
    {
        $this->reset('buku_id');
    }

    public function pinjam()
    {
        $this->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tgl_kembali' => 'required|after_or_equal:tgl_pinjam',
        ]);

        $buku = Buku::find($this->buku_id);

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
                'status_peminjaman' => 'pending', // Kita set 'pending' agar divalidasi petugas
                'denda'             => 0,
            ]);

            $buku->decrement('jumlah');

            LogModel::add('Pinjam', 'Peminjaman', Auth::user()->name . ' meminjam buku: ' . $buku->judul);

            session()->flash('message', 'Berhasil! Tunggu konfirmasi petugas ya.');
            $this->reset('buku_id');
        } else {
            session()->flash('error', 'Stok buku habis!');
        }
    }

    public function render()
    {
        $data_user = Pinjam::where('user_id', Auth::id());

        return view('livewire.siswa.pinjam-buku', [
            'bukus' => Buku::where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('penulis', 'like', '%' . $this->search . '%')
                        ->latest()->paginate(9),
            'bukuTerpilih' => $this->buku_id ? Buku::find($this->buku_id) : null,
            'stat_pinjam' => (clone $data_user)->where('status_peminjaman', 'dipinjam')->count(),
            'stat_denda' => (clone $data_user)->sum('denda'),
            'aktivitas_saya' => (clone $data_user)->latest()->take(5)->get() // Untuk Sidebar status
        ])->layout('layouts.app');
    }
}