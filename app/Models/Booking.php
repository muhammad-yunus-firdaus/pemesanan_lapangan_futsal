<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Booking extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'field_id',
        'booking_time',
        'duration',
        'total_price',
        'status',
    ];

    /**
     * Boot method untuk clear cache saat data berubah
     */
    protected static function booted()
    {
        // Clear cache saat booking dibuat, diupdate, atau dihapus
        static::saved(function () {
            self::clearDashboardCache();
        });

        static::deleted(function () {
            self::clearDashboardCache();
        });
    }

    /**
     * Hapus cache dashboard yang terkait booking
     */
    public static function clearDashboardCache()
    {
        Cache::forget('dashboard_total_bookings');
        Cache::forget('dashboard_total_revenue');
        Cache::forget('dashboard_status_chart');
        Cache::forget('dashboard_popular_fields');
        // Cache dengan key dinamis akan expire sendiri
    }
    /**
     * Kolom yang harus dianggap sebagai tipe Carbon.
     *
     * @var array
     */
    protected $dates = [
        'booking_time', 
        'created_at', 
        'updated_at',
    ];

    /**
     * Konversi tipe data untuk kolom tertentu.
     *
     * @var array
     */
    protected $casts = [
        'booking_time' => 'datetime',
        'total_price' => 'float', // Pastikan tipe total_price dikonversi ke float
        'duration' => 'integer', // Pastikan tipe durasi integer
    ];

    /**
     * Relasi dengan model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model Field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Scope untuk mendapatkan booking milik user tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk mendapatkan booking berdasarkan status tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
