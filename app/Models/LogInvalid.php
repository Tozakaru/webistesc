<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogInvalid extends Model
{
    protected $table = 'log_invalids';
    protected $fillable = ['uid_rfid', 'ruangan', 'waktu'];
    public $timestamps = true;
}

