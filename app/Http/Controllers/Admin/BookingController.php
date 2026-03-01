<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan daftar booking
    public function index()
    {
        $bookings = Booking::with(['user', 'field'])
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    // Form tambah booking
    public function create()
    {
        $fields = Field::all();
        $users = User::all();
        return view('admin.bookings.create', compact('fields', 'users'));
    }

    // Simpan booking baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
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

        if ($overlap) {
            return redirect()->back()->withErrors([
                'booking_time' => 'Lapangan sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.'
            ])->withInput();
        }

        // Hitung total harga
        $field = Field::findOrFail($request->field_id);
        $totalPrice = $field->price_per_hour * $request->duration;

        // Buat booking baru
        Booking::create([
            'user_id' => $request->user_id,
            'field_id' => $request->field_id,
            'booking_time' => $newBookingStart,
            'duration' => $request->duration,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibuat!');
    }

    // Form edit booking
    public function edit(Booking $booking)
    {
        $fields = Field::all();
        $users = User::all();
        return view('admin.bookings.edit', compact('booking', 'fields', 'users'));
    }

    // Update booking
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'field_id' => 'required|exists:fields,id',
            'booking_time' => 'required|date',
            'duration' => 'required|integer|min:1|max:12',
        ]);

        // Hitung waktu mulai dan selesai booking yang akan diupdate
        $newBookingStart = Carbon::parse($request->booking_time);
        $newBookingEnd = $newBookingStart->copy()->addHours($request->duration);

        // Cek overlap dengan booking lain (kecuali booking yang sedang diedit)
        $overlap = Booking::where('field_id', $request->field_id)
            ->where('id', '!=', $booking->id) // Kecuali booking yang sedang diedit
            ->where('status', '!=', 'cancelled') // Hanya cek booking yang aktif
            ->where(function($query) use ($newBookingStart, $newBookingEnd) {
                $query->where(function($q) use ($newBookingStart, $newBookingEnd) {                   
                    $q->whereRaw('booking_time <= ?', [$newBookingStart])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) > ?', [$newBookingStart]);
                })
                ->orWhere(function($q) use ($newBookingStart, $newBookingEnd) {
                    $q->whereRaw('booking_time < ?', [$newBookingEnd])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) >= ?', [$newBookingEnd]);
                })
                ->orWhere(function($q) use ($newBookingStart, $newBookingEnd) {
                    $q->whereRaw('booking_time >= ?', [$newBookingStart])
                      ->whereRaw('DATE_ADD(booking_time, INTERVAL duration HOUR) <= ?', [$newBookingEnd]);
                });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()->withErrors([
                'booking_time' => 'Lapangan sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.'
            ])->withInput();
        }

        // Hitung total harga
        $field = Field::findOrFail($request->field_id);
        $totalPrice = $field->price_per_hour * $request->duration;

        // Update booking
        $booking->update([
            'user_id' => $request->user_id,
            'field_id' => $request->field_id,
            'booking_time' => $newBookingStart,
            'duration' => $request->duration,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil diupdate!');
    }

    // Hapus booking
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus!');
    }

    // Tandai booking selesai
    public function complete(Booking $booking)
    {
        $booking->status = 'completed';
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil ditandai selesai!');
    }

    public function calendar()
    {
        $fields = Field::all();
        $users = User::where('role', 'user')->get();
        return view('admin.bookings.calendar', compact('fields', 'users'));
    }
    public function getCalendarBookings(Request $request)
    {
        try {
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $fieldId = $request->input('field_id');
            $userId = $request->input('user_id');

            // Query bookings untuk bulan dan tahun tertentu
            $query = Booking::with(['user', 'field'])
                ->whereYear('booking_time', $year)
                ->whereMonth('booking_time', $month)
                ->where('status', '!=', 'cancelled');

            // Filter berdasarkan lapangan
            if ($fieldId) {
                $query->where('field_id', $fieldId);
            }

            // Filter berdasarkan user
            if ($userId) {
                $query->where('user_id', $userId);
            }

            $bookings = $query->get();

            // Format data untuk calendar
            $calendarData = [];
            foreach ($bookings as $booking) {
                $date = Carbon::parse($booking->booking_time)->format('Y-m-d');
                $startTime = Carbon::parse($booking->booking_time)->format('H:i');
                $endTime = Carbon::parse($booking->booking_time)->addHours($booking->duration)->format('H:i');

                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }

                $calendarData[$date][] = [
                    'id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'user_name' => $booking->user->name,
                    'field_id' => $booking->field_id,
                    'field_name' => $booking->field->name,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $booking->duration,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $calendarData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kalender: ' . $e->getMessage()
            ], 500);
        }
    }
}