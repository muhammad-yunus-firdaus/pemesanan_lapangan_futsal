<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('field')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('user.bookings.index', compact('bookings'));
    }

    // ========== CALENDAR VIEW ==========
    public function calendar()
    {
        $fields = Field::all();
        return view('user.bookings.calendar', compact('fields'));
    }

// ========== API: GET CALENDAR BOOKINGS ==========
    public function getCalendarBookings(Request $request)
    {
        $fieldId = $request->input('field_id');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get bookings for the selected field and month
        $query = Booking::with(['user', 'field'])
            ->where('status', '!=', 'cancelled')
            ->whereYear('booking_time', $year)
            ->whereMonth('booking_time', $month);

        // Hanya filter lapangan jika dipilih
        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        $bookings = $query->get();

        // Format data untuk calendar
        $calendarData = [];
        foreach ($bookings as $booking) {
            $date = Carbon::parse($booking->booking_time)->format('Y-m-d');
            
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }

            $calendarData[$date][] = [
                'id' => $booking->id,
                'user_name' => $booking->user->name,
                'field_name' => $booking->field->name,
                'field_id' => $booking->field_id, // 🔥 TAMBAHKAN INI
                'start_time' => Carbon::parse($booking->booking_time)->format('H:i'),
                'end_time' => Carbon::parse($booking->booking_time)->addHours($booking->duration)->format('H:i'),
                'duration' => $booking->duration,
                'status' => $booking->status,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $calendarData
        ]);
    }

    public function create()
    {
        $fields = Field::all();
        return view('user.bookings.create', compact('fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:12',
        ]);

        // Hitung waktu mulai dan selesai booking baru
        $newBookingStart = Carbon::parse($request->booking_time);
        $newBookingEnd = $newBookingStart->copy()->addHours($request->duration);

        // Cek overlap dengan booking yang sudah ada
        $overlap = Booking::where('field_id', $request->field_id)
            ->where('status', '!=', 'cancelled') // Hanya cek booking yang aktif
            ->where(function($query) use ($newBookingStart, $newBookingEnd) {
                $query->where(function($q) use ($newBookingStart, $newBookingEnd) {
                    // Kasus 1: Booking baru mulai di tengah-tengah booking lama
                    $q->whereRaw('booking_time <= ?', [$newBookingStart])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) > ?', [$newBookingStart]);
                })
                ->orWhere(function($q) use ($newBookingStart, $newBookingEnd) {
                    // Kasus 2: Booking baru selesai di tengah-tengah booking lama
                    $q->whereRaw('booking_time < ?', [$newBookingEnd])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) >= ?', [$newBookingEnd]);
                })
                ->orWhere(function($q) use ($newBookingStart, $newBookingEnd) {
                    // Kasus 3: Booking baru menutupi seluruh booking lama
                    $q->whereRaw('booking_time >= ?', [$newBookingStart])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) <= ?', [$newBookingEnd]);
                });
            })
            ->exists();

        if($overlap){
            return redirect()->back()->withErrors([
                'booking_time' => 'Lapangan sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.'
            ])->withInput();
        }

        // Hitung total harga
        $field = Field::findOrFail($request->field_id);
        $totalPrice = $field->price_per_hour * $request->duration;

        // Buat booking baru
        Booking::create([
            'user_id' => Auth::id(),
            'field_id' => $request->field_id,
            'booking_time' => $request->booking_time,
            'duration' => $request->duration,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('user.bookings.index')->with('success', 'Booking berhasil dibuat!');
    }

    public function show(Booking $booking)
    {
        if($booking->user_id != Auth::id()){
            abort(403, 'Unauthorized action.');
        }

        return view('user.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        if($booking->user_id != Auth::id()){
            abort(403, 'Unauthorized action.');
        }

        // Batalkan booking
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('user.bookings.index')->with('success', 'Booking berhasil dibatalkan!');
    }
}