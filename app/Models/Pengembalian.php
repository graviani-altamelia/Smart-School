<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Perbaikan import
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini

class Pengembalian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengembalians';
    protected $fillable = ['pinjam_id', 'tgl_kembali', 'denda'];

    public function pinjam(): BelongsTo
    {
        return $this->belongsTo(Pinjam::class, 'pinjam_id');
    }
}