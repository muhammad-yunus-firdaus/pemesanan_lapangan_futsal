@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan')

@section('content')
<div class="container-fluid py-2">
    <div class="mb-4">
        <a href="{{ route('user.bookings.index') }}" class="btn-modern btn-modern-light py-2">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-modern overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5" style="min-height: 250px;">
                        @if($booking->field->image)
                            <img src="{{ asset('storage/' . $booking->field->image) }}" alt="{{ $booking->field->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i data-lucide="image" class="text-muted opacity-30" style="width: 64px; height: 64px;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h3 class="fw-bold mb-1">{{ $booking->field->name }}</h3>
                                <p class="text-muted small">{{ $booking->field->description ?? 'Lapangan Futsal' }}</p>
                            </div>
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

                        <div class="row g-4 mb-5">
                            <div class="col-6">
                                <div class="text-muted small fw-bold mb-1">TANGGAL</div>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->booking_time)->translatedFormat('d M Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small fw-bold mb-1">JAM</div>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small fw-bold mb-1">DURASI</div>
                                <div class="fw-bold">{{ $booking->duration }} Jam</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small fw-bold mb-1">ID PESANAN</div>
                                <div class="fw-bold text-uppercase">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>

                        <div class="total-payment-box p-3 bg-light rounded-lg">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-muted small">Total Pembayaran</span>
                                <span class="fw-bold text-success total-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-modern p-4 h-100">
                <h5 class="fw-bold mb-4">Aksi Pesanan</h5>
                
                @if($booking->status === 'pending')
                    <p class="text-muted small mb-4">Pesanan Anda sedang menunggu konfirmasi admin. Anda masih dapat membatalkan pesanan ini.</p>
                    <div class="mb-3">
                        <button type="button" 
                            class="btn-modern btn-modern-light text-danger border-danger w-100 py-3" 
                            onclick="confirmCancelShow()">
                            <i data-lucide="trash-2"></i> Batalkan Pesanan
                        </button>
                        <form id="cancel-form-show" action="{{ route('user.bookings.destroy', $booking->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                @elseif($booking->status === 'completed')
                    <div class="text-center py-4">
                        <div class="bg-success-light p-3 rounded-circle d-inline-flex mb-3">
                            <i data-lucide="check-circle" class="text-success" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h6 class="fw-bold">Pesanan Selesai</h6>
                        <p class="text-muted small">Terima kasih telah menggunakan fasilitas Lembang Arena.</p>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="bg-primary-light p-3 rounded-circle d-inline-flex mb-3">
                            <i data-lucide="info" class="text-primary" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h6 class="fw-bold">Status: {{ ucfirst($booking->status) }}</h6>
                        <p class="text-muted small">Status pesanan Anda saat ini adalah {{ $booking->status }}.</p>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold mb-3 small">Bantuan?</h6>
                    <a href="#" class="btn-modern btn-modern-light w-100 py-2 small gap-2">
                        <i data-lucide="message-square" style="width: 14px;"></i> Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.object-fit-cover { object-fit: cover; }
.bg-warning-light { background-color: #fffbeb; }
.bg-primary-light { background-color: #eef2ff; }
.bg-success-light { background-color: #ecfdf5; }
.bg-danger-light { background-color: #fef2f2; }
.rounded-lg { border-radius: var(--radius-lg); }

/* Total Payment Box Styling */
.total-payment-box {
    border: 1px solid rgba(16, 185, 129, 0.2);
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%) !important;
}
.total-price {
    font-size: 1.1rem;
    color: #10b981 !important;
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .col-lg-8 .card-modern .row.g-0 {
        flex-direction: column;
    }
    .col-lg-8 .card-modern .col-md-5 {
        min-height: 200px !important;
    }
}

@media (max-width: 767.98px) {
    .col-lg-8 .card-modern .p-4.p-md-5 {
        padding: 1.25rem !important;
    }
    .col-lg-8 .card-modern h3 {
        font-size: 1.25rem;
    }
    .col-lg-8 .card-modern .row.g-4.mb-5 {
        margin-bottom: 1.5rem !important;
    }
    .col-lg-8 .card-modern .row.g-4.mb-5 .col-6 {
        padding: 0.5rem !important;
    }
    .total-payment-box {
        padding: 0.75rem !important;
    }
    .total-price {
        font-size: 1rem;
    }
    .col-lg-4 .card-modern {
        padding: 1rem !important;
    }
}

@media (max-width: 575.98px) {
    .container-fluid.py-2 {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    .col-lg-8 .card-modern .col-md-5 {
        min-height: 150px !important;
    }
    .col-lg-8 .card-modern .p-4.p-md-5 {
        padding: 1rem !important;
    }
    .col-lg-8 .card-modern h3 {
        font-size: 1.1rem;
    }
    .col-lg-8 .card-modern .d-flex.justify-content-between.align-items-start {
        flex-direction: column;
        gap: 0.5rem;
    }
    .col-lg-8 .card-modern .badge-modern {
        align-self: flex-start;
    }
    .total-payment-box .d-flex {
        flex-direction: column;
        text-align: center;
        gap: 0.25rem;
    }
    .total-price {
        font-size: 1.1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});

function confirmCancelShow() {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Apakah Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Batalkan Pesanan!',
        cancelButtonText: 'Tidak, Kembali',
        reverseButtons: true,
        borderRadius: '16px',
        customClass: {
            title: 'fw-bold',
            popup: 'rounded-4 shadow-lg border-0'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form-show').submit();
        }
    });
}
</script>
@endsection
