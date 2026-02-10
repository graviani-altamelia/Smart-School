<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\SoftDeletes;
use App\Models\Pengembalian;

class Pengembalian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='pengembalians';
    protected $primaryKey='id';
    protected $fillable=['id', 'pinjam_id', 'tgl_kembali', 'denda'];

    public function pinjam():BelongsTo
    {
        return $this->belongsTo(Pinjam::class);
    }
}
