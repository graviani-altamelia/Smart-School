<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pinjam extends Model
{
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }
    public function buku() { return $this->belongsTo(Buku::class); }

    /**
     * Menghitung denda secara otomatis
     */
    public function hitungDenda()
    {
        $batasKembali = Carbon::parse($this->tgl_kembali);
        $hariIni = Carbon::now();

        // Jika status sudah kembali, pakai tgl_realisasi_kembali (jika ada)
        // Jika belum kembali, bandingkan dengan hari ini
        if ($this->status == 'dipinjam' && $hariIni->gt($batasKembali)) {
            $selisihHari = $hariIni->diffInDays($batasKembali);
            return $selisihHari * 1000; // Contoh: Rp 1.000 per hari
        }

        return 0;
    }
}