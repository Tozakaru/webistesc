<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspDevice extends Model
{
    protected $fillable = ['nama_kelas', 'last_seen'];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
