<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model User - nyimpen data user yang daftar di sistem
 * Bisa jadi admin atau user biasa, dibedakan pake kolom 'role'
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Kolom yang boleh diisi waktu create/update
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone_number',
        'password',
        'role',
    ];

    // Kolom yang disembunyiin waktu convert ke JSON (biar password ga keexpose)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data biar otomatis diconvert
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke Booking - satu user bisa punya banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
