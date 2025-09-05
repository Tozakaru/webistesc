<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // kolom yang bisa di-mass assign
    protected $fillable = [
        'name',
        'username',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // casting seperlunya (tanpa 'password' => 'hashed')
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
