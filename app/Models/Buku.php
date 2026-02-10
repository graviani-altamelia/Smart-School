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

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}