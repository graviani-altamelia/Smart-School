<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pinjam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'buku_id', 'judul', 'tgl_pinjam', 
        'tgl_kembali', 'tgl_kembali_asli', 'jumlah_pinjam', 
        'status_peminjaman', 'denda'
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
        'tgl_kembali_asli' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function buku() { return $this->belongsTo(Buku::class)->withTrashed(); }

    // Fungsi cek terlambat (dipakai di Dashboard & Pengembalian)
    public function getIsTerlambatAttribute()
    {
        if ($this->status_peminjaman === 'dikembalikan') return false;
        return Carbon::now('Asia/Jakarta')->startOfDay()->gt(Carbon::parse($this->tgl_kembali)->startOfDay());
    }

    // Fungsi hitung denda (dipakai di Dashboard & Pengembalian)
    public function hitungDenda()
    {
        if ($this->status_peminjaman === 'dikembalikan') return (int) ($this->denda ?? 0);
        
        $tenggat = Carbon::parse($this->tgl_kembali)->startOfDay();
        $hariIni = Carbon::now('Asia/Jakarta')->startOfDay();

        if ($hariIni->gt($tenggat)) {
            $selisihHari = $hariIni->diffInDays($tenggat);
            return (int) ($selisihHari * 1000 * $this->jumlah_pinjam);
        }
        return 0;
    }
}