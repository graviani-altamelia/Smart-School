<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Pinjam;
use App\Models\Buku;
use App\Models\User;
use App\Models\LogAktivitas as LogModel;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Peminjaman extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $isEdit = false;
    public $showModal = false;

    // Properti Form
    public $pinjam_id, $user_id, $buku_id, $tgl_pinjam, $tgl_kembali, $jumlah_pinjam = 1;

    public function render()
    {
        $data = Pinjam::with(['user', 'buku'])
            ->whereIn('status_peminjaman', ['pending', 'dipinjam'])
            ->where(function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('buku', function($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()->paginate(10);

        return view('livewire.petugas.peminjaman', [
            'peminjaman' => $data,
            'users' => User::where('role', 'siswa')->get(),
            'bukus' => Buku::where('jumlah', '>', 0)->get()
        ])->layout('layouts.app');
    }

    // --- FITUR CRUD ---

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $this->pinjam_id = $id;
        $this->user_id = $pinjam->user_id;
        $this->buku_id = $pinjam->buku_id;
        $this->tgl_pinjam = $pinjam->tgl_pinjam;
        $this->tgl_kembali = $pinjam->tgl_kembali;
        $this->jumlah_pinjam = $pinjam->jumlah_pinjam;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'user_id' => 'required',
            'buku_id' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
        ]);

        $buku = Buku::find($this->buku_id);

        if ($this->isEdit) {
            $pinjam = Pinjam::find($this->pinjam_id);
            $pinjam->update([
                'user_id' => $this->user_id,
                'buku_id' => $this->buku_id,
                'judul' => $buku->judul,
                'tgl_pinjam' => $this->tgl_pinjam,
                'tgl_kembali' => $this->tgl_kembali,
                'jumlah_pinjam' => $this->jumlah_pinjam,
            ]);
            session()->flash('message', 'Data peminjaman berhasil diperbarui.');
        } else {
            // Create langsung status 'dipinjam' dan potong stok
            Pinjam::create([
                'user_id' => $this->user_id,
                'buku_id' => $this->buku_id,
                'judul' => $buku->judul,
                'tgl_pinjam' => $this->tgl_pinjam,
                'tgl_kembali' => $this->tgl_kembali,
                'jumlah_pinjam' => $this->jumlah_pinjam,
                'status_peminjaman' => 'dipinjam',
            ]);
            $buku->decrement('jumlah', $this->jumlah_pinjam);
            session()->flash('message', 'Peminjaman manual berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        // Jika dihapus saat status 'dipinjam', balikkan stoknya
        if ($pinjam->status_peminjaman == 'dipinjam') {
            $pinjam->buku->increment('jumlah', $pinjam->jumlah_pinjam);
        }
        $pinjam->delete();
        session()->flash('message', 'Data peminjaman berhasil dihapus.');
    }

    // --- LOGIKA VALIDASI (YANG SUDAH ADA) ---

    public function setujui($id) {
        $pinjam = Pinjam::findOrFail($id);
        $buku = Buku::find($pinjam->buku_id);
        if ($buku->jumlah < $pinjam->jumlah_pinjam) {
            session()->flash('error', 'Stok tidak cukup!');
            return;
        }
        $pinjam->update(['status_peminjaman' => 'dipinjam']);
        $buku->decrement('jumlah', $pinjam->jumlah_pinjam);
        session()->flash('message', 'Disetujui!');
    }

    public function tolak($id) {
        Pinjam::findOrFail($id)->update(['status_peminjaman' => 'ditolak']);
    }

    public function kembalikan($id) {
        $pinjam = Pinjam::findOrFail($id);
        $denda = 0;
        if (now()->gt($pinjam->tgl_kembali)) {
            $denda = now()->diffInDays($pinjam->tgl_kembali) * 1000;
        }
        $pinjam->update([
            'status_peminjaman' => 'dikembalikan',
            'tgl_kembali_asli' => now(),
            'denda' => $denda 
        ]);
        $pinjam->buku->increment('jumlah', $pinjam->jumlah_pinjam);
        session()->flash('message', 'Buku kembali. Denda: Rp' . $denda);
    }

    public function closeModal() { $this->showModal = false; $this->resetForm(); }
    private function resetForm() {
        $this->reset(['user_id', 'buku_id', 'tgl_pinjam', 'tgl_kembali', 'jumlah_pinjam', 'pinjam_id']);
    }
}