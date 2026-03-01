<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * HomeController - handle dashboard user setelah login
 * Tampilkan ringkasan booking dan match yang akan datang
 */
class HomeController extends Controller
{
    public function __construct()
    {
        // Wajib login dulu baru bisa akses
        $this->middleware('auth');
    }

    // Halaman dashboard user
    public function index()
    {
        $user = auth()->user();
        
        // Hitung statistik booking user
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'active_bookings' => $user->bookings()->whereIn('status', ['confirmed', 'completed'])->count(),
            'total_spent' => $user->bookings()->where('status', 'completed')->sum('total_price'),
        ];

        // Cari booking terdekat yang belum selesai
        $upcomingMatch = $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_time', '>=', now())
            ->with('field')
            ->orderBy('booking_time', 'asc')
            ->first();

        return view('home', compact('stats', 'upcomingMatch'));
    }
}
