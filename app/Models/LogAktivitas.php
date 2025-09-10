<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $guarded = [];
    public $timestamps = false;

    // Agar sorting & formatting waktu akurat
    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
        'tanggal'      => 'date',
    ];

    public function mahasiswa(): BelongsTo
    {
        // Ubah 'mahasiswa_id' jika foreign key berbeda
        return $this->belongsTo(Mahasiswa::class);
    }
}
