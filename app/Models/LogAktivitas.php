<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    // Samakan deskripsi/keterangan agar tidak bingung
    protected $fillable = ['user_id', 'aksi', 'kategori', 'deskripsi', 'ip_address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function add($aksi, $kategori, $deskripsi)
    {
        return self::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'kategori' => $kategori,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}