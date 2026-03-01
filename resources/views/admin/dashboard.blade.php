@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    <!-- WELCOME SECTION -->
    <div class="card-modern p-3 p-md-4 mb-4" style="border-radius: var(--radius-lg);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p class="mb-0 text-muted small">Panel kontrol sistem Lembang Arena siap digunakan.</p>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="small text-muted fw-bold mb-1">{{ now()->translatedFormat('l, d F Y') }}</div>
                <div class="fw-bold fs-4 text-primary" id="real-time-clock" style="letter-spacing: 0.5px;">{{ now()->translatedFormat('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card-modern p-3 border-0 bg-white shadow-sm" style="background: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5) !important;">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="{{ route('admin.bookings.create') }}" class="btn-capsule btn-capsule-primary">
                        <i data-lucide="plus-circle" style="width: 16px;"></i> Tambah Booking
                    </a>
                    <a href="{{ route('admin.fields.create') }}" class="btn-capsule btn-capsule-primary">
                        <i data-lucide="plus-circle" style="width: 16px;"></i> Tambah Lapangan
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn-capsule btn-capsule-primary">
                        <i data-lucide="user-plus" style="width: 16px;"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK UTAMA -->
    <div class="row g-3 mb-4">
        <!-- Total Pemesanan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card h-100 hover-lift shadow-sm">
                <div class="stat-content">
                    <div class="stat-label">Total Pemesanan</div>
                    <div class="stat-value text-primary">{{ number_format($totalBookings) }}</div>
                    <div class="stat-subtext text-muted small mt-1">
                        <i data-lucide="calendar" style="width: 12px;" class="text-primary"></i> <span class="fw-medium">{{ number_format($bookingsToday) }} Hari Ini</span>
                    </div>
                </div>
                <div class="stat-icon-new bg-primary bg-opacity-10 text-primary">
                    <i data-lucide="clipboard-list"></i>
                </div>
            </div>
        </div>

        <!-- Total Lapangan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card h-100 hover-lift shadow-sm">
                <div class="stat-content">
                    <div class="stat-label">Total Lapangan</div>
                    <div class="stat-value text-success">{{ number_format($totalFields) }}</div>
                    <div class="stat-subtext text-muted small mt-1">
                        <i data-lucide="trending-up" style="width: 12px;" class="text-success"></i> <span class="fw-medium">{{ $occupancyRate }}% Penggunaan</span>
                    </div>
                </div>
                <div class="stat-icon-new bg-success bg-opacity-10 text-success">
                    <i data-lucide="layout"></i>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card h-100 hover-lift shadow-sm">
                <div class="stat-content">
                    <div class="stat-label">Total Pengguna</div>
                    <div class="stat-value text-warning">{{ number_format($totalUsers) }}</div>
                    <div class="stat-subtext text-muted small mt-1">
                        <i data-lucide="clock" style="width: 12px;" class="text-warning"></i> <span class="fw-medium">{{ $bookingsPending }} Pending</span>
                    </div>
                </div>
                <div class="stat-icon-new bg-warning bg-opacity-10 text-warning">
                    <i data-lucide="users"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card h-100 hover-lift shadow-sm">
                <div class="stat-content">
                    <div class="stat-label">Total Keseluruhan</div>
                    <div class="stat-value revenue-compact text-success">
                        <span class="fs-6 fw-normal text-muted">Rp</span>
                        <span>{{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="stat-subtext text-muted small mt-1 text-nowrap">
                        <i data-lucide="trending-up" style="width: 12px;" class="text-success"></i> <span class="fw-bold text-success">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span> <span class="ms-1">Bulan Ini</span>
                    </div>
                </div>
                <div class="stat-icon-new bg-success bg-opacity-10 text-success">
                    <i data-lucide="wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- CORE DASHBOARD DATA -->
    <div class="row g-3 mb-4">
        <!-- Recent Bookings (Main Section) -->
        <div class="col-12">
            <div class="card-modern border-0 h-100 overflow-hidden">
                <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-light">
                    <div>
                        <h6 class="mb-0 fw-bold text-heading">Booking Terbaru</h6>
                        <p class="text-muted small mb-0">Kelola pesanan yang baru masuk</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="btn-capsule btn-capsule-light text-primary border bg-white">
                        Lihat Semua <i data-lucide="chevron-right" style="width: 14px;"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if($recentBookings->isEmpty())
                        <div class="p-5 text-center">
                            <i data-lucide="inbox" class="text-muted mb-2" style="width: 48px; height: 48px;"></i>
                            <p class="text-muted">Belum ada booking terdaftar</p>
                        </div>
                    @else
                        <!-- Desktop Table View -->
                        <div class="d-none d-md-block">
                            <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Customer</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Lapangan</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Waktu</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status</th>
                                        <th class="pe-4 py-3 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-small bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid var(--border-light);">
                                                    <i data-lucide="user" style="width: 14px;"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $booking->user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted small fw-medium">{{ $booking->field->name }}</span>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-heading">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('d M Y') }}</div>
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($booking->status === 'completed')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" style="font-size: 0.7rem;">Selesai</span>
                                            @elseif($booking->status === 'cancelled')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2" style="font-size: 0.7rem;">Batal</span>
                                            @elseif($booking->status === 'confirmed')
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2" style="font-size: 0.7rem;">Dikonfirmasi</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2" style="font-size: 0.7rem;">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="p-2 text-muted hover-primary transition-base rounded-3">
                                                <i data-lucide="more-horizontal" style="width: 18px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-block d-md-none">
                            <div class="p-3">
                                @foreach($recentBookings as $booking)
                                <div class="mobile-stat-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-small bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid var(--border-light);">
                                                <i data-lucide="user" style="width: 14px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-heading" style="font-size: 0.9rem;">{{ $booking->user->name }}</div>
                                                <div class="text-muted small">{{ $booking->field->name }}</div>
                                            </div>
                                        </div>
                                        @if($booking->status === 'completed')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">Selesai</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.65rem;">Batal</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Dikonfirmasi</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1" style="font-size: 0.65rem;">Menunggu</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="small fw-bold text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} • {{ \Carbon\Carbon::parse($booking->booking_time)->format('d M') }}</div>
                                    </div>
                                    <div class="text-end mt-2">
                                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-light border py-1 px-3 text-primary" style="font-size: 0.75rem; border-radius: 8px;">
                                            Detail Pesanan
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PERFORMANCE TREND -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-bold text-heading">Statistik Aktivitas Mingguan</h6>
                </div>
                <div class="chart-body">
                    <canvas id="bookingTrendChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>



</div>

<style>
/* ========== VARIABLES ========== */
:root {
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --purple-color: #8b5cf6;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
}

/* ========== STAT CARDS (REFINED) ========== */
.stat-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    border: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: var(--transition-base);
}

.stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-heading);
}

.revenue-compact {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}

.stat-icon-new {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.bg-primary-light { background-color: #f0f9ff; }
.bg-success-light { background-color: #ecfdf5; }
.bg-warning-light { background-color: #fffbeb; }

/* ========== CAPSULE BUTTONS ========== */
.btn-capsule {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: var(--radius-full);
    text-decoration: none;
    transition: var(--transition-base);
    box-shadow: var(--shadow-sm);
}

.btn-capsule:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-capsule-primary {
    background-color: var(--primary);
    color: white;
}

.quick-actions-card {
    border-left: 4px solid var(--primary);
}

/* ========== REMOVE OLD STYLES ========== */
.stat-blue, .stat-green, .stat-red, .stat-orange, .info-bar, .info-divider, .action-btn { display: none; }

.btn-capsule-light {
    background-color: #f8fafc;
    color: var(--text-heading);
}

.hover-primary:hover {
    background-color: #eef2ff;
    color: var(--primary) !important;
}

.bg-primary-subtle { background-color: #eef2ff; }
.bg-success-subtle { background-color: #f0fdf4; }
.bg-warning-subtle { background-color: #fffbeb; }
.bg-danger-subtle { background-color: #fef2f2; }

/* ========== CHART CARD ========== */
.chart-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.chart-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f3f4f6;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
}

.chart-header h6 {
    color: #1f2937;
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
}

.chart-body {
    padding: 1.25rem;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-body canvas {
    width: 100% !important;
    max-height: 250px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .core-dashboard-grid {
        display: flex;
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .stat-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }

    .btn-capsule {
        width: auto;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .stat-card {
        padding: 0.75rem;
    }
    
    .stat-label {
        font-size: 0.688rem;
        margin-bottom: 0.375rem;
    }
    
    .stat-value {
        font-size: 1.375rem;
    }
    
    .revenue-amount {
        font-size: 1rem;
    }
    
    .revenue-currency {
        font-size: 0.625rem;
    }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 1.125rem;
    }
    
    .info-item i {
        font-size: 1.25rem;
    }
    
    .info-value {
        font-size: 1rem;
    }
    
    .info-text {
        font-size: 0.688rem;
    }
    
    .chart-header h6,
    .table-header h6 {
        font-size: 0.875rem;
    }
    
    .action-btn {
        padding: 0.5rem 0.875rem;
        font-size: 0.75rem;
        gap: 0.5rem;
    }
    
    .action-btn i {
        font-size: 0.875rem;
    }
    
    h4.fw-semibold {
        font-size: 1.125rem;
    }
    
    p.text-muted {
        font-size: 0.813rem;
    }

    .mobile-stat-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
    }

    .stat-card {
        padding: 0.75rem !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.5rem;
    }

    .stat-icon-new {
        width: 32px;
        height: 32px;
        font-size: 1rem;
        order: -1;
    }

    .stat-value {
        font-size: 1.15rem;
    }

    .stat-label {
        font-size: 0.65rem;
    }

    .btn-capsule {
        width: 100%;
        justify-content: center;
        padding: 0.5rem;
    }
}

@media (max-width: 992px) {
    .main-wrapper {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .sidebar-modern:not(.show) {
        transform: translateX(-100%) !important;
    }
    .content-body {
        padding: 0.75rem !important;
    }
}


/* ========== CHART RESPONSIVE ========== */
canvas {
    max-width: 100%;
    height: auto !important;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

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

    document.addEventListener('DOMContentLoaded', function() {
    
    // Konfigurasi warna
    const colors = {
        primary: '#3b82f6',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#06b6d4',
        purple: '#8b5cf6'
    };

    // LINE CHART: Tren Booking
    const trendCtx = document.getElementById('bookingTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($last7Days) !!},
                datasets: [{
                    label: 'Booking',
                    data: {!! json_encode($bookingTrend) !!},
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.04)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }


});
</script>
@endsection