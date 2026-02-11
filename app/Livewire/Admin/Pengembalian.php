<?php

namespace App\Models; // Hanya pengingat: Pastikan model Pinjam sudah punya $fillable = ['status_peminjaman']

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pinjam;
use App\Models\Buku;
use App\Models\LogAktivitas; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Pengembalian extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Pinjam::with(['user', 'buku'])
            ->where('status_peminjaman', 'like', 'dipinjam') 
            ->where(function($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        $loans->getCollection()->transform(function($loan) {
            $tenggat = Carbon::parse($loan->tgl_kembali)->startOfDay();
            $hari_ini = Carbon::now()->startOfDay();
            
            $loan->terlambat = 0;
            $loan->denda = 0;

            if ($hari_ini->gt($tenggat)) {
                $loan->terlambat = $hari_ini->diffInDays($tenggat);
                $loan->denda = $loan->terlambat * 1000; 
            }
            return $loan;
        });

        return view('livewire.admin.pengembalian', [
            'loans' => $loans
        ])->layout('layouts.app');
    }

    public function kembalikan($id)
    {
        // Gunakan Transaction agar jika log error, database tetap aman (consistent)
        DB::beginTransaction();

        try {
            $pinjam = Pinjam::with(['user', 'buku'])->findOrFail($id);
            
            $tenggat = Carbon::parse($pinjam->tgl_kembali)->startOfDay();
            $hari_ini = Carbon::now()->startOfDay();
            $dendaTotal = $hari_ini->gt($tenggat) ? $hari_ini->diffInDays($tenggat) * 1000 : 0;

            // 1. Update status
            $pinjam->update([
                'status_peminjaman' => 'kembali',
            ]);

            // 2. Balikin stok buku
            if ($pinjam->buku) {
                $pinjam->buku->increment('jumlah', $pinjam->jumlah_pinjam);
            }

            // 3. Log Aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aksi' => 'Kembali',
                'keterangan' => 'Menerima kembali: ' . $pinjam->judul . ' dari ' . ($pinjam->user->name ?? 'Siswa') . '. Denda: Rp' . number_format($dendaTotal)
            ]);

            DB::commit();
            session()->flash('message', 'Buku berhasil dikembalikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }
}