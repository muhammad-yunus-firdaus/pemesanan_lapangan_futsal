<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Model Field - data lapangan futsal
 * Nyimpen info nama, harga per jam, deskripsi, sama gambar lapangan
 */
class Field extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'name', 
        'price_per_hour', 
        'description', 
        'image'
    ];

    /**
     * Auto clear cache tiap kali data field berubah
     * Jadi dashboard selalu dapet data terbaru
     */
    protected static function booted()
    {
        static::saved(function () {
            self::clearFieldCache();
        });

        static::deleted(function () {
            self::clearFieldCache();
        });
    }

    /**
     * Hapus semua cache yang nyimpen data field
     */
    public static function clearFieldCache()
    {
        Cache::forget('user_fields_list');
        Cache::forget('dashboard_total_fields');
        Cache::forget('dashboard_popular_fields');
    }

    /**
     * Relasi ke Booking - satu lapangan bisa dipake banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
