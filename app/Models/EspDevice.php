<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspDevice extends Model
{
    protected $fillable = ['code', 'nama_kelas', 'last_seen'];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function getRouteKeyName() { return 'code'; }

    public function logs()     { return $this->hasMany(\App\Models\LogAktivitas::class, 'esp_device_id'); }
    public function invalids() { return $this->hasMany(\App\Models\LogInvalid::class,   'esp_device_id'); }
}
