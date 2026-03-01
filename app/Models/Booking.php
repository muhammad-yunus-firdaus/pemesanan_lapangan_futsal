<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Model Booking - data pemesanan lapangan
 * Nyimpen siapa yang booking, lapangan mana, kapan, berapa lama, dan statusnya
 */
class Booking extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'field_id',
        'booking_time',
        'duration',
        'total_price',
        'status',
    ];

    // Kolom tanggal biar otomatis jadi Carbon instance
    protected $dates = [
        'booking_time', 
        'created_at', 
        'updated_at',
    ];

    // Casting tipe data
    protected $casts = [
        'booking_time' => 'datetime',
        'total_price' => 'float',
        'duration' => 'integer',
    ];

    /**
     * Auto clear cache tiap kali data booking berubah
     * Biar statistik di dashboard selalu update
     */
    protected static function booted()
    {
        static::saved(function () {
            self::clearDashboardCache();
        });

        static::deleted(function () {
            self::clearDashboardCache();
        });
    }

    /**
     * Hapus cache dashboard yang nyimpen data booking
     */
    public static function clearDashboardCache()
    {
        Cache::forget('dashboard_total_bookings');
        Cache::forget('dashboard_total_revenue');
        Cache::forget('dashboard_status_chart');
        Cache::forget('dashboard_popular_fields');
    }

    /**
     * Relasi ke User - booking ini punya siapa
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Field - booking ini untuk lapangan mana
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Scope buat filter booking berdasarkan user
     * Pakenya: Booking::forUser($userId)->get()
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope buat filter booking berdasarkan status
     * Pakenya: Booking::withStatus('confirmed')->get()
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
