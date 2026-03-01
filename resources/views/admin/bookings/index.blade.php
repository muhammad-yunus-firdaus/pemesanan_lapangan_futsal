@extends('layouts.admin')

@section('title', 'Data Pemesanan')
@section('page_title', 'Kelola Booking')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <!-- Header Section -->
    <div class="card-modern p-4 mb-4" style="border-radius: var(--radius-lg);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Daftar Pemesanan Lapangan</h4>
                <p class="mb-0 text-muted small">Kelola seluruh data pesanan dan status penggunaan lapangan.</p>
            </div>
            <a href="{{ route('admin.bookings.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                <i data-lucide="plus" style="width: 18px;"></i> Tambah Booking
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="check-circle" style="width: 18px;"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="card-modern p-3 mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i data-lucide="search" style="width: 14px;"></i>
                    </span>
                    <input type="text" id="bookingSearch" class="form-control border-start-0 ps-0" placeholder="Cari nama pengguna atau lapangan...">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Batal</option>
                </select>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <button class="btn btn-sm btn-light border w-100 text-muted d-flex align-items-center justify-content-center gap-2">
                    <i data-lucide="filter" style="width: 14px;"></i> Filter
                </button>
            </div>
        </div>
    </div>

    @if ($bookings->isEmpty())
        <div class="card-modern p-5 text-center">
            <div class="py-4">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i data-lucide="inbox" class="text-muted" style="width: 32px; height: 32px;"></i>
                </div>
                <h5 class="text-heading fw-bold">Belum Ada Data</h5>
                <p class="text-muted small mb-4">Mulai kelola pesanan dengan menambahkan data baru.</p>
                <a href="{{ route('admin.bookings.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                    <i data-lucide="plus" style="width: 18px;"></i> Tambah Pesanan Pertama
                </a>
            </div>
        </div>
    @else
        <!-- DESKTOP TABLE VIEW -->
        <div class="card-modern border-0 overflow-hidden d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Customer</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Lapangan</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Waktu Booking</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Durasi</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Harga</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status</th>
                            <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase" style="font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bookingTableBody">
                        @foreach ($bookings as $booking)
                        <tr class="booking-row" data-status="{{ $booking->status }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-small bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid var(--border-light);">
                                        <i data-lucide="user" style="width: 14px;"></i>
                                    </div>
                                    <span class="fw-semibold text-heading search-name">{{ $booking->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small fw-medium search-field">{{ $booking->field->name }}</span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-heading">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($booking->booking_time)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                    {{ $booking->duration }} Jam
                                </span>
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
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="p-2 text-muted hover-primary transition-base rounded-3" title="Edit">
                                        <i data-lucide="edit-3" style="width: 16px;"></i>
                                    </a>

                                    @if($booking->status !== 'completed' && $booking->status !== 'cancelled')
                                    <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2 text-muted hover-success transition-base rounded-3 border-0 bg-transparent" title="Selesaikan">
                                            <i data-lucide="check-circle" style="width: 16px;"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pemesanan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-muted hover-danger transition-base rounded-3 border-0 bg-transparent" title="Hapus">
                                            <i data-lucide="trash-2" style="width: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE CARD VIEW -->
        <div class="d-block d-md-none">
            <div id="bookingCardList">
                @foreach ($bookings as $booking)
                <div class="card-modern p-3 mb-3 booking-card-mobile animate-fade-in" data-status="{{ $booking->status }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-small bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i data-lucide="user" style="width: 14px;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-heading search-name">{{ $booking->user->name }}</div>
                                <div class="text-muted small search-field">{{ $booking->field->name }}</div>
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

                    <div class="bg-light p-2 rounded-3 mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700;">Waktu</div>
                                <div class="small fw-bold text-heading">{{ \Carbon\Carbon::parse($booking->booking_time)->translatedFormat('d M, H:i') }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700;">Total</div>
                                <div class="small fw-bold text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-light border flex-grow-1 py-2 text-heading fw-semibold" style="font-size: 0.75rem; border-radius: 8px;">
                            <i data-lucide="edit-3" class="me-1" style="width: 14px;"></i> Edit
                        </a>
                        @if($booking->status !== 'completed' && $booking->status !== 'cancelled')
                        <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="flex-grow-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success-subtle text-success border-success-subtle w-100 py-2 fw-semibold" style="font-size: 0.75rem; border-radius: 8px;">
                                <i data-lucide="check-circle" class="me-1" style="width: 14px;"></i> Selesai
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="flex-grow-0" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger-subtle text-danger border-danger-subtle p-2" style="border-radius: 8px;">
                                <i data-lucide="trash-2" style="width: 14px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .hover-primary:hover { background-color: #f0f9ff; color: var(--primary) !important; }
    .hover-success:hover { background-color: #ecfdf5; color: var(--success) !important; }
    .hover-danger:hover { background-color: #fef2f2; color: var(--danger) !important; }
    
    .bg-primary-subtle { background-color: #f0f9ff; }
    .bg-success-subtle { background-color: #ecfdf5; }
    .bg-warning-subtle { background-color: #fffbeb; }
    .bg-danger-subtle { background-color: #fef2f2; }
    
    .btn-success-subtle { background-color: #ecfdf5; }
    .btn-danger-subtle { background-color: #fef2f2; }

    .booking-card-mobile {
        transition: var(--transition-base);
    }
    
    .booking-card-mobile:hover {
        transform: translateY(-2px);
    }

    /* Animation */
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bookingSearch');
        const statusFilter = document.getElementById('statusFilter');
        
        function filterBookings() {
            const query = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            
            // Desktop context
            const desktopRows = document.querySelectorAll('.booking-row');
            desktopRows.forEach(row => {
                const name = row.querySelector('.search-name').textContent.toLowerCase();
                const field = row.querySelector('.search-field').textContent.toLowerCase();
                const rowStatus = row.dataset.status;
                
                const matchesSearch = name.includes(query) || field.includes(query);
                const matchesStatus = status === '' || rowStatus === status;
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
            
            // Mobile context
            const mobileCards = document.querySelectorAll('.booking-card-mobile');
            mobileCards.forEach(card => {
                const name = card.querySelector('.search-name').textContent.toLowerCase();
                const field = card.querySelector('.search-field').textContent.toLowerCase();
                const cardStatus = card.dataset.status;
                
                const matchesSearch = name.includes(query) || field.includes(query);
                const matchesStatus = status === '' || cardStatus === status;
                
                card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterBookings);
        statusFilter.addEventListener('change', filterBookings);
    });
</script>
@endsection
