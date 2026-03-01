<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // Quick Stats
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'active_bookings' => $user->bookings()->whereIn('status', ['confirmed', 'completed'])->count(),
            'total_spent' => $user->bookings()->where('status', 'completed')->sum('total_price'),
        ];

        // Upcoming Match: Include both pending and confirmed matches that haven't occurred yet
        $upcomingMatch = $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_time', '>=', now())
            ->with('field')
            ->orderBy('booking_time', 'asc')
            ->first();

        return view('home', compact('stats', 'upcomingMatch'));
    }
}
