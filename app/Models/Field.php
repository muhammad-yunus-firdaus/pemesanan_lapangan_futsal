<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Field extends Model
{
    use HasFactory;

    // Tambahkan 'image' supaya bisa diisi lewat mass assignment
    protected $fillable = [
        'name', 'price_per_hour', 'description', 'image'
    ];

    /**
     * Boot method untuk clear cache saat data berubah
     */
    protected static function booted()
    {
        // Clear cache saat field dibuat, diupdate, atau dihapus
        static::saved(function () {
            self::clearFieldCache();
        });

        static::deleted(function () {
            self::clearFieldCache();
        });
    }

    /**
     * Hapus cache yang terkait field
     */
    public static function clearFieldCache()
    {
        Cache::forget('user_fields_list');
        Cache::forget('dashboard_total_fields');
        Cache::forget('dashboard_popular_fields');
    }

    // Relasi dengan model Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
