<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $fillable = ['user_id', 'aksi', 'kategori', 'deskripsi', 'ip_address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function add($aksi, $kategori, $deskripsi)
    {
        self::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'kategori' => $kategori,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}