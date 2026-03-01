<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * AdminController - handle halaman dashboard admin
 * Nampilin statistik, grafik, dan ringkasan data booking
 */
class AdminController extends Controller
{
    /**
     * Redirect ke dashboard (kalo ada yang akses /admin aja)
     */
    public function index()
    {
        return $this->dashboard();
    }

    /**
     * Halaman utama admin - dashboard dengan semua statistik
     * Data di-cache biar ga berat tiap kali load
     */
    public function dashboard()
    {
        // Hitung total user, lapangan, booking (cache 5 menit)
        $totalUsers = Cache::remember('dashboard_total_users', 300, function () {
            return User::count();
        });
        
        $totalFields = Cache::remember('dashboard_total_fields', 300, function () {
            return Field::count();
        });
        
        $totalBookings = Cache::remember('dashboard_total_bookings', 300, function () {
            return Booking::count();
        });

        // Hitung total pendapatan dari booking yang confirmed/completed
        $totalRevenue = Cache::remember('dashboard_total_revenue', 300, function () {
            return Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        });
        
        // Pendapatan bulan ini aja
        $monthKey = 'dashboard_monthly_revenue_' . Carbon::now()->format('Y_m');
        $monthlyRevenue = Cache::remember($monthKey, 300, function () {
            return Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('booking_time', Carbon::now()->month)
                ->whereYear('booking_time', Carbon::now()->year)
                ->sum('total_price');
        });

        // Data yang sering berubah - ga pake cache
        $bookingsToday = Booking::whereDate('booking_time', Carbon::today())->count();
        $bookingsPending = Booking::where('status', 'pending')->count();

        // Hitung tingkat okupansi (persen lapangan terpakai bulan ini)
        $occupancyKey = 'dashboard_occupancy_' . Carbon::now()->format('Y_m');
        $occupancyRate = Cache::remember($occupancyKey, 600, function () use ($totalFields) {
            $daysInMonth = Carbon::now()->daysInMonth;
            $operatingHours = 14; // jam operasional 08:00-22:00
            $maxSlots = $totalFields * $daysInMonth * $operatingHours;
            
            $bookingsThisMonth = Booking::whereMonth('booking_time', Carbon::now()->month)
                ->whereYear('booking_time', Carbon::now()->year)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('duration');
            
            return $maxSlots > 0 ? round(($bookingsThisMonth / $maxSlots) * 100, 1) : 0;
        });

        // Data untuk line chart - tren booking 7 hari terakhir
        $trendKey = 'dashboard_trend_' . Carbon::today()->format('Y_m_d');
        $trendData = Cache::remember($trendKey, 300, function () {
            $last7Days = [];
            $bookingTrend = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $last7Days[] = $date->format('d M');
                $count = Booking::whereDate('booking_time', $date)->count();
                $bookingTrend[] = $count;
            }
            
            return ['days' => $last7Days, 'trend' => $bookingTrend];
        });
        
        $last7Days = $trendData['days'];
        $bookingTrend = $trendData['trend'];

        // Data untuk bar chart - ranking lapangan berdasarkan jumlah booking
        $popularData = Cache::remember('dashboard_popular_fields', 600, function () {
            $popularFields = Booking::select('field_id', DB::raw('COUNT(*) as total'))
                ->with('field:id,name')
                ->whereIn('status', ['confirmed', 'completed'])
                ->groupBy('field_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            return [
                'names' => $popularFields->pluck('field.name')->toArray(),
                'bookings' => $popularFields->pluck('total')->toArray()
            ];
        });
        
        $fieldNames = $popularData['names'];
        $fieldBookings = $popularData['bookings'];

        // Data untuk pie chart - distribusi status booking
        $statusChartData = Cache::remember('dashboard_status_chart', 300, function () {
            $statusDistribution = Booking::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get();

            $statusLabels = [];
            $statusData = [];
            $statusColors = [
                'pending' => '#fbbf24',
                'confirmed' => '#3b82f6',
                'completed' => '#10b981',
                'cancelled' => '#ef4444',
            ];
            $chartColors = [];

            foreach ($statusDistribution as $status) {
                $statusLabels[] = ucfirst($status->status);
                $statusData[] = $status->total;
                $chartColors[] = $statusColors[$status->status] ?? '#6b7280';
            }
            
            return ['labels' => $statusLabels, 'data' => $statusData, 'colors' => $chartColors];
        });
        
        $statusLabels = $statusChartData['labels'];
        $statusData = $statusChartData['data'];
        $chartColors = $statusChartData['colors'];

        // Ambil 5 booking terbaru (ga di-cache biar selalu fresh)
        $recentBookings = Booking::with(['user:id,name', 'field:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFields',
            'totalBookings',
            'totalRevenue',
            'monthlyRevenue',
            'bookingsToday',
            'bookingsPending',
            'occupancyRate',
            'last7Days',
            'bookingTrend',
            'fieldNames',
            'fieldBookings',
            'statusLabels',
            'statusData',
            'chartColors',
            'recentBookings'
        ));
    }
}