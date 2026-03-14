<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Buku extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'kategori_id', 
        'judul', 
        'penulis', 
        'penerbit', 
        'tahun', 
        'jumlah'
    ];

    /**
     * Relasi ke Tabel Kategori
     * Menghilangkan error RelationNotFoundException
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke Tabel Pinjam
     */
    public function pinjams()
    {
        return $this->hasMany(Pinjam::class);
    }

    /**
     * Logic Stok: Mengurangi stok di database
     */
    public function kurangiStok($qty)
    {
        if ($this->jumlah >= $qty) {
            $this->decrement('jumlah', $qty);
            return true;
        }
        return false;
    }

    /**
     * Logic Stok: Menambah stok (saat buku kembali atau riwayat dihapus)
     */
    public function tambahStok($qty)
    {
        $this->increment('jumlah', $qty);
    }
}