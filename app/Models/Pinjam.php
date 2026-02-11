<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pinjam extends Model
{
    protected $fillable = [
        'user_id', 
        'buku_id', 
        'judul', 
        'tgl_pinjam', 
        'tgl_kembali', 
        'jumlah_pinjam', 
        'status_peminjaman'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function buku() { return $this->belongsTo(Buku::class); }

    public function hitungDenda()
    {
        if ($this->status_peminjaman === 'dipinjam') {
            $batas = Carbon::parse($this->tgl_kembali)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();
            
            if ($hariIni->gt($batas)) {
                return $hariIni->diffInDays($batas) * 1000;
            }
        }
        return 0;
    }
}