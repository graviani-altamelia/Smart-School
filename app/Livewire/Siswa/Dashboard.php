<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $selectedBuku, $tgl_pinjam, $tgl_kembali, $jumlah_pinjam = 1;

    protected $paginationTheme = 'bootstrap';

    // PENGAMAN: Mencegah Admin/Petugas masuk ke dashboard siswa
    public function mount()
    {
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('home');
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function pinjam($id)
    {
        $this->selectedBuku = Buku::find($id);
        
        if (!$this->selectedBuku || $this->selectedBuku->jumlah <= 0) {
            $this->dispatch('swal:error', message: 'Stok buku habis!');
            return;
        }

        $this->tgl_pinjam = Carbon::now()->format('Y-m-d');
        $this->tgl_kembali = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->jumlah_pinjam = 1;
        
        $this->showModal = true;
    }

    public function konfirmasiPinjam()
    {
        $bukuDB = Buku::find($this->selectedBuku->id);

        if ($this->jumlah_pinjam > $bukuDB->jumlah) {
            $this->dispatch('swal:error', message: 'Stok tidak mencukupi!');
            return;
        }

        Pinjam::create([
            'user_id' => Auth::id(),
            'buku_id' => $bukuDB->id,
            'judul'   => $bukuDB->judul,
            'tgl_pinjam' => $this->tgl_pinjam,
            'tgl_kembali' => $this->tgl_kembali, 
            'jumlah_pinjam' => $this->jumlah_pinjam,
            'status_peminjaman' => 'pending' 
        ]);

        $bukuDB->update([
            'jumlah' => $bukuDB->jumlah - $this->jumlah_pinjam
        ]);

        $this->showModal = false;
        $this->search = ''; 
        
        $this->dispatch('swal:success', message: 'Permintaan pinjam berhasil dikirim!');
    }

    public function render()
    {
        $bukus = Buku::where('judul', 'like', '%'.$this->search.'%')
                     ->latest()
                     ->paginate(8);

        $peminjaman_terbaru = Pinjam::where('user_id', Auth::id())->get();

        return view('livewire.siswa.dashboard', [
            'bukus' => $bukus,
            'peminjaman_saya' => $peminjaman_terbaru->sortByDesc('created_at'),
            'pinjam_aktif' => $peminjaman_terbaru->whereIn('status_peminjaman', ['pending', 'dipinjam'])->sum('jumlah_pinjam'),
            'total_denda' => $peminjaman_terbaru->sum(fn($p) => method_exists($p, 'hitungDenda') ? $p->hitungDenda() : 0)
        ])->layout('layouts.app'); // PASTIKAN file resources/views/layouts/app.blade.php ADA
    }
}