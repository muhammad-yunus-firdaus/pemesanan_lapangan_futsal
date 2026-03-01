@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="py-2">
    <!-- Header Greeting -->
    <div class="card-modern p-4 mb-4 border-0" style="border-radius: var(--radius-lg); background: linear-gradient(135deg, white 0%, var(--primary-light) 100%);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Halo, {{ Auth::user()->name }}!</h4>
                <p class="mb-0 text-muted small">Waktunya bertanding! Cari lapangan favoritmu sekarang.</p>
            </div>
            <div class="text-end">
                <div class="small text-muted fw-bold mb-1">{{ now()->translatedFormat('l, d F Y') }}</div>
                <div class="fw-bold fs-4 text-primary" id="real-time-clock" style="letter-spacing: 0.5px;">{{ now()->translatedFormat('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-modern p-4 h-100 hover-lift border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                        <i data-lucide="clipboard-list" style="width: 20px;"></i>
                    </div>
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary small fw-bold px-3 py-1">Total</span>
                </div>
                <h3 class="fw-bold mb-1 text-primary">{{ $stats['total_bookings'] }}</h3>
                <p class="text-muted small mb-0 fw-medium">Total Pemesanan Anda</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern p-4 h-100 hover-lift border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success">
                        <i data-lucide="check-circle" style="width: 20px;"></i>
                    </div>
                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success small fw-bold px-3 py-1">Aktif</span>
                </div>
                <h3 class="fw-bold mb-1 text-success">{{ $stats['active_bookings'] }}</h3>
                <p class="text-muted small mb-0 fw-medium">Booking Terkonfirmasi</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern p-4 h-100 hover-lift border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success">
                        <i data-lucide="wallet" style="width: 20px;"></i>
                    </div>
                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success small fw-bold px-3 py-1">Total Pengeluaran</span>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                    <h6 class="text-muted small fw-bold mb-1 text-uppercase">Total</h6>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Upcoming Match -->
            @if($upcomingMatch)
                <div class="card-modern p-4 mb-4 border-start border-4 border-primary shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-heading">Pertandingan Terdekat</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-bold">Mendatang</span>
                    </div>
                    <div class="d-flex align-items-center gap-4 bg-light bg-opacity-50 p-3 rounded-4 border">
                        <div class="text-center px-3 border-end">
                            <div class="fw-bold fs-3 text-primary mb-0 lh-1">{{ $upcomingMatch->booking_time->format('d') }}</div>
                            <div class="text-muted small text-uppercase fw-bold">{{ $upcomingMatch->booking_time->format('M') }}</div>
                        </div>
                        <div>
                            <div class="fw-bold text-heading fs-5 mb-1">{{ $upcomingMatch->field->name }}</div>
                            <div class="d-flex gap-3">
                                <span class="text-muted small d-flex align-items-center gap-1">
                                    <i data-lucide="clock" style="width:14px"></i> {{ $upcomingMatch->booking_time->format('H:i') }}
                                </span>
                                <span class="text-muted small d-flex align-items-center gap-1">
                                    <i data-lucide="hourglass" style="width:14px"></i> {{ $upcomingMatch->duration }} Jam
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-modern p-5 mb-4 text-center bg-light border-dashed" style="border: 2px dashed #e2e8f0;">
                    <div class="bg-white p-3 rounded-circle d-inline-flex mb-3 shadow-sm">
                        <i data-lucide="calendar" class="text-muted" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-heading">Belum Ada Pertandingan</h5>
                    <p class="text-muted small mb-3">Ayo mulai cari lapangan dan pesan jadwal Anda untuk mulai bermain!</p>
                    <a href="{{ route('user.fields.index') }}" class="btn-capsule btn-capsule-primary px-4">
                        Cari Lapangan Sekarang
                    </a>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('user.fields.index') }}" class="card-modern p-4 text-decoration-none h-100 d-flex flex-column hover-lift shadow-sm border-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3" style="width: fit-content;">
                            <i data-lucide="search" class="text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-heading">Cari Lapangan</h6>
                        <p class="text-muted small mb-0">Temukan lapangan terbaik untuk pertandingan Anda.</p>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('user.bookings.index') }}" class="card-modern p-4 text-decoration-none h-100 d-flex flex-column hover-lift shadow-sm border-0">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3" style="width: fit-content;">
                            <i data-lucide="calendar" class="text-info"></i>
                        </div>
                        <h6 class="fw-bold text-heading">Jadwal Saya</h6>
                        <p class="text-muted small mb-0">Lihat semua riwayat dan status pesanan Anda.</p>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card-modern p-4 h-100 border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.6) !important; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5) !important;">
                <h6 class="fw-bold mb-4 d-flex align-items-center gap-2 text-heading">
                    <i data-lucide="star" class="text-warning fill-warning" style="width: 18px;"></i>
                    Tips Professional
                </h6>
                <div class="d-flex gap-3 mb-4">
                    <div class="bg-white p-2 rounded-circle flex-shrink-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i data-lucide="zap" class="text-warning" style="width: 18px;"></i>
                    </div>
                    <p class="small text-muted mb-0 fw-medium">Lakukan reservasi minimal 24 jam sebelumnya untuk menjamin ketersediaan lapangan.</p>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="bg-white p-2 rounded-circle flex-shrink-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i data-lucide="shield-check" class="text-success" style="width: 18px;"></i>
                    </div>
                    <p class="small text-muted mb-0 fw-medium">Pastikan Anda datang 15 menit sebelum jadwal dimulai.</p>
                </div>
                <div class="d-flex gap-3 p-3 rounded-4 border border-primary border-opacity-10" style="background-color: var(--primary-light);">
                    <div class="bg-white p-2 rounded-circle flex-shrink-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i data-lucide="award" class="text-primary" style="width: 18px;"></i>
                    </div>
                    <div>
                        <p class="small mb-0 fw-bold text-heading">Keamanan Terjamin</p>
                        <p class="extra-small text-muted mb-0">Gunakan perlengkapan yang sesuai untuk menjaga keamanan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
}
.rounded-lg { border-radius: 0.75rem; }
.extra-small { font-size: 0.75rem; }
</style>
@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('real-time-clock');
        if (clock) {
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            clock.textContent = h + ':' + m;
        }
    }
    setInterval(updateClock, 1000);
</script>
@endpush
@endsection
