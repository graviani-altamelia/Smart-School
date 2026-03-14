<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Buku;
use App\Models\Pinjam;
use App\Models\LogAktivitas as LogModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DaftarBuku extends Component
{
    use WithPagination;

    // Properti Utama
    public $search = ''; 
    public $showModal = false; // Pastikan ini ada!
    public $selectedBuku; // Objek buku yang dipilih
    
    // Properti Form
    public $buku_id; 
    public $tgl_pinjam;
    public $tgl_kembali;
    public $jumlah_pinjam = 1;

    protected $paginationTheme = 'bootstrap'; 

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Method untuk membuka modal
    public function pinjam($id)
    {
        $this->selectedBuku = Buku::find($id);
        
        if ($this->selectedBuku) {
            $this->buku_id = $id;
            $this->jumlah_pinjam = 1;
            $this->tgl_pinjam = now()->format('Y-m-d');
            $this->tgl_kembali = now()->addDays(3)->format('Y-m-d');
            $this->showModal = true; // Tampilkan modal
        }
    }

    // Method eksekusi simpan
    public function pinjamBuku()
    {
        $this->validate([
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
        ], [
            'tgl_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.'
        ]);

        $buku = Buku::find($this->buku_id);

        if ($buku && $buku->jumlah >= $this->jumlah_pinjam) {
            
            Pinjam::create([
                'user_id'           => Auth::id(),
                'buku_id'           => $this->buku_id,
                'judul'             => $buku->judul,
                'tgl_pinjam'        => $this->tgl_pinjam,
                'tgl_kembali'       => $this->tgl_kembali,
                'jumlah_pinjam'     => $this->jumlah_pinjam,
                'status_peminjaman' => 'pending', 
            ]);

            // Potong stok otomatis (opsional, tergantung kebijakan perpustakaan)
            $buku->decrement('jumlah', $this->jumlah_pinjam);

            LogModel::add('Request', 'Siswa', Auth::user()->name . ' mengajukan pinjam buku: ' . $buku->judul);

            session()->flash('message', 'Permintaan pinjam berhasil dikirim! Stok buku telah diperbarui.');
            
            $this->showModal = false; // Tutup modal
            $this->reset(['buku_id', 'jumlah_pinjam', 'selectedBuku']);
        } else {
            session()->flash('error', 'Maaf, stok buku tidak mencukupi.');
        }
    }

    public function render()
    {
        $bukus = Buku::where(function($query) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(12); 

        return view('livewire.siswa.daftar-buku', [
            'bukus' => $bukus
        ])->layout('layouts.app');
    }
}