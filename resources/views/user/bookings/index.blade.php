@extends('layouts.app')

@section('title', 'Pemesanan Saya')
@section('page_title', 'Riwayat Pesanan')

@section('content')
<div class="py-2">
    <!-- Header Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1">Daftar Pesanan</h3>
            <p class="text-muted">Kelola dan pantau seluruh riwayat booking lapangan Anda.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('user.fields.index') }}" class="btn-elegant">
                <i data-lucide="plus-circle" style="width: 20px;"></i>
                <span>Buat Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4 d-flex align-items-center gap-2">
            <i data-lucide="check-circle" style="width: 18px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($bookings->isEmpty())
        <div class="card-modern p-5 text-center">
            <div class="p-4 bg-light rounded-circle d-inline-flex mb-4">
                <i data-lucide="calendar-x" class="text-muted" style="width: 40px; height: 40px;"></i>
            </div>
            <h5 class="fw-bold">Belum Ada Pesanan</h5>
            <p class="text-muted mb-4">Anda belum memiliki riwayat booking lapangan saat ini.</p>
            <a href="{{ route('user.fields.index') }}" class="btn-modern btn-modern-primary">
                Explore Lapangan
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($bookings as $booking)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card-modern h-100 d-flex flex-column">
                        <div class="p-4 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">{{ $booking->field->name }}</h5>
                                <span class="badge-modern 
                                    @if($booking->status == 'pending') badge-warning 
                                    @elseif($booking->status == 'confirmed') badge-info
                                    @elseif($booking->status == 'completed') badge-success
                                    @else badge-danger @endif">
                                    @if($booking->status == 'pending') MENUNGGU 
                                    @elseif($booking->status == 'confirmed') DIKONFIRMASI
                                    @elseif($booking->status == 'completed') SELESAI
                                    @else DIBATALKAN @endif
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i data-lucide="calendar" style="width: 14px;"></i>
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->translatedFormat('d M Y') }}
                                </div>
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i data-lucide="clock" style="width: 14px;"></i>
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} ({{ $booking->duration }} Jam)
                                </div>
                                <div class="d-flex align-items-center gap-2 small">
                                    <i data-lucide="tag" class="text-success" style="width: 14px;"></i>
                                    <span class="text-success fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-light bg-opacity-50 border-top d-flex gap-2">
                            <a href="{{ route('user.bookings.show', $booking->id) }}" class="btn-modern btn-modern-light flex-grow-1 py-1">
                                Detail
                            </a>
                            @if($booking->status === 'pending')
                                <div class="flex-grow-1">
                                    <button type="button" 
                                        class="btn-modern btn-modern-light text-danger border-danger w-100 py-1" 
                                        onclick="confirmCancel('{{ $booking->id }}')">
                                        Batal
                                    </button>
                                    <form id="cancel-form-{{ $booking->id }}" action="{{ route('user.bookings.destroy', $booking->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.bg-warning-light { background-color: #fffbeb; }
.bg-primary-light { background-color: #eef2ff; }
.bg-success-light { background-color: #ecfdf5; }
.bg-danger-light { background-color: #fef2f2; }
.space-y-3 > * + * { margin-top: 0.75rem; }
.rounded-lg { border-radius: var(--radius-lg); }

.btn-elegant {
    background-color: var(--primary);
    color: white !important;
    border: none;
    padding: 0.5rem 1.25rem;
    font-size: 0.95rem;
    border-radius: 8px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    transition: all 0.2s ease;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(14, 165, 233, 0.1);
}

.btn-elegant:hover {
    background-color: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(14, 165, 233, 0.2);
    color: white !important;
}

.btn-elegant:active {
    transform: translateY(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});

function confirmCancel(bookingId) {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Pesanan Anda akan dibatalkan. Tindakan ini tidak dapat dibatalkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        borderRadius: '16px',
        customClass: {
            title: 'fw-bold',
            popup: 'rounded-4 shadow-lg border-0'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form-' + bookingId).submit();
        }
    });
}
</script>
@endsection