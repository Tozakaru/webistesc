<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // nama tabel (optional, kalau Laravel sudah otomatis jamak jadi 'dosens')
    protected $table = 'dosens';

    // kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'uid_rfid',
        'status_uid',
    ];

    // relasi: satu dosen bisa punya banyak log_aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'dosen_id');
    }
}
