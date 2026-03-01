@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Daftar Booking Anda</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($bookings->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nama Field</th>
                        <th>Waktu Booking</th>
                        <th>Durasi (jam)</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->field->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_time)->translatedFormat('d M Y, H:i') }}</td>
                        <td>{{ $booking->duration }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($booking->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status === 'pending')
                                <form action="{{ route('user.bookings.destroy', $booking->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Batalkan</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center mt-5">
            <p class="text-muted">Anda belum memiliki booking.</p>
        </div>
    @endif
</div>
@endsection
