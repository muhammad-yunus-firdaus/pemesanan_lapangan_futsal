@extends('layouts.admin')

@section('title', 'Jadwal Booking - Admin')
@section('page_title', 'Jadwal Booking Lapangan')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <!-- Header Section -->
    <div class="card-modern p-4 mb-4" style="border-radius: var(--radius-lg);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Jadwal Booking Lapangan</h4>
                <p class="mb-0 text-muted small">Pantau ketersediaan slot dan kelola reservasi secara real-time.</p>
            </div>
            <div class="d-flex gap-2 gap-md-3">
                <button class="btn-capsule btn-capsule-light border bg-white px-3 px-md-4 py-2 flex-grow-1 flex-md-grow-0" id="refresh_calendar">
                    <i data-lucide="refresh-cw" style="width: 16px;"></i> <span class="d-none d-sm-inline">Refresh</span>
                </button>
                <a href="{{ route('admin.bookings.create') }}" class="btn-capsule btn-capsule-primary px-3 px-md-4 py-2 flex-grow-1 flex-md-grow-0">
                    <i data-lucide="plus-circle" style="width: 18px;"></i> <span class="d-none d-sm-inline">Tambah</span> Booking
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card-modern p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="field_filter" class="form-label fw-semibold text-heading small">Filter Lapangan</label>
                <div class="position-relative">
                    <i data-lucide="map-pin" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 16px;"></i>
                    <select class="input-modern ps-5" id="field_filter">
                        <option value="">Semua Lapangan</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="user_filter" class="form-label fw-semibold text-heading small">Filter User</label>
                <div class="position-relative">
                    <i data-lucide="user" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 16px;"></i>
                    <select class="input-modern ps-5" id="user_filter">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-4">
                <label class="form-label fw-semibold text-heading small">Bulan & Tahun</label>
                <div class="input-group input-group-modern">
                    <button class="btn btn-light border px-3" type="button" id="prev_month">
                        <i data-lucide="chevron-left" style="width: 16px;"></i>
                    </button>
                    <input type="month" class="form-control text-center fw-bold px-1" id="month_picker" value="{{ now()->format('Y-m') }}">
                    <button class="btn btn-light border px-3" type="button" id="next_month">
                        <i data-lucide="chevron-right" style="width: 16px;"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <div class="bg-primary-subtle p-2 rounded-3 text-center border border-primary-subtle h-100 d-flex flex-column justify-content-center">
                    <div class="text-primary small fw-bold text-uppercase" style="font-size: 0.6rem;">Hari Ini</div>
                    <div class="text-primary fw-bold small">{{ now()->translatedFormat('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="card-modern border-0 overflow-hidden mb-4">
        <div class="p-3 bg-light border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <h6 class="mb-0 fw-bold text-heading d-flex align-items-center gap-2">
                <i data-lucide="calendar-days" class="text-primary" style="width: 18px;"></i>
                <span id="current_month_display">Memuat...</span>
            </h6>
            <div class="d-flex flex-wrap gap-2 gap-md-3">
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-success" style="width: 6px; height: 6px;"></span> <span class="text-muted" style="font-size: 0.65rem;">Kosong</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-warning" style="width: 6px; height: 6px;"></span> <span class="text-muted" style="font-size: 0.65rem;">Ada Slot</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-danger" style="width: 6px; height: 6px;"></span> <span class="text-muted" style="font-size: 0.65rem;">Penuh</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="loading_calendar" class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"></div>
                <p class="mt-3 text-muted small fw-medium">Sinkronisasi Jadwal...</p>
            </div>
            <div id="calendar_container" class="d-none">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 calendar-table-modern" id="calendar_table">
                        <thead>
                            <tr class="bg-light text-center">
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase d-none d-lg-table-cell">Minggu</th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase">
                                    <span class="d-none d-md-inline">Senin</span>
                                    <span class="d-none d-sm-inline d-md-none">Sen</span>
                                    <span class="d-inline d-sm-none">S</span>
                                </th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase">
                                    <span class="d-none d-md-inline">Selasa</span>
                                    <span class="d-none d-sm-inline d-md-none">Sel</span>
                                    <span class="d-inline d-sm-none">S</span>
                                </th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase">
                                    <span class="d-none d-md-inline">Rabu</span>
                                    <span class="d-none d-sm-inline d-md-none">Rab</span>
                                    <span class="d-inline d-sm-none">R</span>
                                </th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase">
                                    <span class="d-none d-md-inline">Kamis</span>
                                    <span class="d-none d-sm-inline d-md-none">Kam</span>
                                    <span class="d-inline d-sm-none">K</span>
                                </th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase">
                                    <span class="d-none d-md-inline">Jumat</span>
                                    <span class="d-none d-sm-inline d-md-none">Jum</span>
                                    <span class="d-inline d-sm-none">J</span>
                                </th>
                                <th class="py-2 py-md-3 text-muted small fw-bold text-uppercase d-none d-lg-table-cell">Sabtu</th>
                            </tr>
                        </thead>
                        <tbody id="calendar_body">
                            <!-- Generated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card-modern p-3 mb-4 bg-light bg-opacity-50">
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 shadow-sm border">
                    <span class="badge bg-info-subtle text-info border-info-subtle rounded-pill" style="width: 10px; height: 10px; padding: 0;"></span>
                    <span class="small fw-bold text-heading">Pending</span>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 shadow-sm border">
                    <span class="badge bg-primary-subtle text-primary border-primary-subtle rounded-pill" style="width: 10px; height: 10px; padding: 0;"></span>
                    <span class="small fw-bold text-heading">Confirmed</span>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 shadow-sm border">
                    <span class="badge bg-success-subtle text-success border-success-subtle rounded-pill" style="width: 10px; height: 10px; padding: 0;"></span>
                    <span class="small fw-bold text-heading">Selesai</span>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 shadow-sm border">
                    <span class="badge bg-danger-subtle text-danger border-danger-subtle rounded-pill" style="width: 10px; height: 10px; padding: 0;"></span>
                    <span class="small fw-bold text-heading">Penuh/Batal</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Slots Modal -->
    <div class="modal fade" id="timeSlotsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
                <div class="modal-header bg-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-circle">
                            <i data-lucide="clock" class="text-white" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modal_date_title">Detail Jadwal</h5>
                            <p class="mb-0 small text-white-50">Silakan pilih slot waktu yang tersedia</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Filters inside Modal -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-heading small">Filter Lapangan</label>
                            <select class="input-modern" id="modal_field_filter" style="height: 40px; font-size: 0.85rem;">
                                <option value="">Semua Lapangan</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-heading small">Filter User</label>
                            <select class="input-modern" id="modal_user_filter" style="height: 40px; font-size: 0.85rem;">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="time_slots_loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div id="time_slots_container" class="d-none">
                        <!-- Slots generated via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ========== CALENDAR STYLES ========== */
.calendar-table-modern {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    table-layout: fixed;
}

.calendar-table-modern th {
    border: none;
    border-bottom: 1px solid var(--border-light);
}

.calendar-table-modern td {
    height: 120px;
    vertical-align: top;
    padding: 12px !important;
    position: relative;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
    border: 0.5px solid var(--border-light);
}

.calendar-table-modern td:hover {
    background: var(--primary-light) !important;
    z-index: 5;
    box-shadow: inset 0 0 0 2px var(--primary);
}

.date-number {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text-heading);
    margin-bottom: 4px;
    opacity: 0.8;
}

.past-date {
    background-color: #f8fafc !important;
    cursor: default !important;
}

.past-date .date-number {
    color: #cbd5e1;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.date-indicator {
    font-size: 0.65rem;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 8px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Time Slot Item Modern */
.time-slot-item {
    border-radius: var(--radius-md);
    padding: 16px;
    margin-bottom: 12px;
    border-left: 5px solid;
    transition: all 0.2s ease;
    background: white;
    box-shadow: var(--shadow-sm);
}

.time-slot-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.slot-available { border-color: var(--success); background-color: #f0fdf4; }
.slot-booked { border-color: var(--danger); background-color: #fef2f2; }
.slot-pending { border-color: var(--info); background-color: #f0f9ff; }
.slot-confirmed { border-color: var(--primary); background-color: #eff6ff; }

/* Responsive Adjustments */
/* Responsive Adjustments */
@media (max-width: 1200px) {
    .calendar-table-modern td {
        height: 100px;
        padding: 8px !important;
    }
}

@media (max-width: 991px) {
    .calendar-table-modern td {
        height: 90px;
    }
    .date-number { font-size: 0.95rem; }
}

.today-badge {
    background: var(--primary);
    color: white;
    border-radius: 4px;
    padding: 1px 4px;
    font-size: 0.55rem;
    font-weight: 800;
}

.booking-count {
    color: var(--primary);
    font-weight: 800;
    font-size: 0.6rem;
}

.booking-dot {
    position: absolute;
    bottom: 4px;
    right: 4px;
    background: var(--primary);
    color: white;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.55rem;
    font-weight: 900;
}

@media (max-width: 767px) {
    .calendar-table-modern td {
        height: auto;
        min-height: 70px;
        padding: 4px !important;
    }
    .date-number { font-size: 0.85rem; }
    .today-badge { 
        padding: 1px 2px;
        font-size: 0.45rem;
        border-radius: 2px;
    }
    .date-indicator { 
        font-size: 0.55rem;
        padding: 1px 4px;
        letter-spacing: -0.2px;
    }
    .date-indicator .dot { width: 5px; height: 5px; }
    
    /* Hide Saturday and Sunday on mobile to save space */
    .calendar-table-modern th.d-none.d-lg-table-cell,
    .calendar-table-modern td.d-none.d-lg-table-cell {
        display: none !important;
    }

    .indicator-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
}

@media (max-width: 575px) {
    .calendar-table-modern td {
        min-height: 60px;
    }
    .today-badge {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        padding: 0;
        text-indent: -9999px;
    }
    .date-indicator span:not(.dot) {
        display: none; /* Only show dot on extra small screens */
    }
    .date-indicator {
        padding: 0;
        justify-content: center;
        background: none !important;
        border: none !important;
    }
    .date-indicator .dot { width: 6px; height: 6px; }
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentMonth = new Date().getMonth() + 1;
    let currentYear = new Date().getFullYear();
    let bookingsData = {};
    let selectedFieldId = '';
    let selectedUserId = '';
    let currentModalDate = '';

    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Event Listeners
    document.getElementById('field_filter').addEventListener('change', function() {
        selectedFieldId = this.value;
        loadCalendar();
    });

    document.getElementById('user_filter').addEventListener('change', function() {
        selectedUserId = this.value;
        loadCalendar();
    });

    document.getElementById('month_picker').addEventListener('change', function() {
        const [year, month] = this.value.split('-');
        currentYear = parseInt(year);
        currentMonth = parseInt(month);
        loadCalendar();
    });

    document.getElementById('prev_month').addEventListener('click', function() {
        if (currentMonth === 1) {
            currentMonth = 12;
            currentYear--;
        } else {
            currentMonth--;
        }
        updateMonthPicker();
        loadCalendar();
    });

    document.getElementById('next_month').addEventListener('click', function() {
        if (currentMonth === 12) {
            currentMonth = 1;
            currentYear++;
        } else {
            currentMonth++;
        }
        updateMonthPicker();
        loadCalendar();
    });

    document.getElementById('refresh_calendar').addEventListener('click', function() {
        loadCalendar();
    });

    // Modal Field & User Filter
    document.getElementById('modal_field_filter').addEventListener('change', function() {
        renderTimeSlots(currentModalDate);
    });

    document.getElementById('modal_user_filter').addEventListener('change', function() {
        renderTimeSlots(currentModalDate);
    });

    function updateMonthPicker() {
        const monthStr = currentMonth.toString().padStart(2, '0');
        document.getElementById('month_picker').value = `${currentYear}-${monthStr}`;
    }

    function loadCalendar() {
        document.getElementById('loading_calendar').classList.remove('d-none');
        document.getElementById('calendar_container').classList.add('d-none');

        const url = new URL('{{ route("admin.bookings.calendar.data") }}');
        url.searchParams.append('month', currentMonth);
        url.searchParams.append('year', currentYear);
        if (selectedFieldId) url.searchParams.append('field_id', selectedFieldId);
        if (selectedUserId) url.searchParams.append('user_id', selectedUserId);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bookingsData = data.data;
                    renderCalendar();
                    document.getElementById('loading_calendar').classList.add('d-none');
                    document.getElementById('calendar_container').classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data kalender.');
            });
    }

    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        document.getElementById('current_month_display').textContent = 
            `${monthNames[currentMonth - 1]} ${currentYear}`;

        let html = '<tr>';
        const startDay = firstDay === 0 ? 6 : firstDay - 1; // Adjust for Monday start

        for (let i = 0; i < startDay; i++) {
            html += '<td class="d-none d-lg-table-cell border-0 bg-light p-0"></td>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const cellDate = new Date(currentYear, currentMonth - 1, day);
            const isPast = cellDate < today;
            const isToday = cellDate.getTime() === today.getTime();
            const bookings = bookingsData[dateStr] || [];
            
            let indicator = '';
            let cellClass = '';
            
            if (isPast) {
                cellClass = 'past-date';
                indicator = '<div class="date-indicator text-muted"><i data-lucide="history" style="width:10px"></i> Lewat</div>';
            } else if (bookings.length === 0) {
                indicator = '<div class="date-indicator text-success"><span class="dot bg-success me-1"></span> Kosong</div>';
            } else if (bookings.length < 5) {
                indicator = '<div class="date-indicator text-warning"><span class="dot bg-warning me-1"></span> Ada Slot</div>';
            } else {
                indicator = '<div class="date-indicator text-danger"><span class="dot bg-danger me-1"></span> Penuh</div>';
            }

            const satSunClass = ((startDay + day) % 7 === 0 || (startDay + day) % 7 === 6) ? 'bg-light bg-opacity-25' : '';

            html += `
                <td class="${cellClass} ${satSunClass} ${isToday ? 'border-primary border-2 shadow-sm' : ''}" 
                    data-date="${dateStr}" onclick="showTimeSlots('${dateStr}')">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="date-number">${day}</div>
                        ${isToday ? '<span class="today-badge">Today</span>' : ''}
                    </div>
                    <div class="indicator-wrapper">
                        ${indicator}
                    </div>
                    ${bookings.length > 0 ? `<div class="mt-1 booking-count d-none d-sm-block">${bookings.length} Pesanan</div>` : ''}
                    ${bookings.length > 0 ? `<div class="booking-dot d-sm-none">${bookings.length}</div>` : ''}
                </td>
            `;

            if ((startDay + day) % 7 === 0 && day !== daysInMonth) {
                html += '</tr><tr>';
            }
        }

        const totalCells = startDay + daysInMonth;
        const remainingCells = (7 - (totalCells % 7)) % 7;
        for (let i = 0; i < remainingCells; i++) {
            html += '<td class="d-none d-lg-table-cell border-0 bg-light p-0"></td>';
        }
        html += '</tr>';

        document.getElementById('calendar_body').innerHTML = html;
        if (window.lucide) lucide.createIcons();
    }

    window.showTimeSlots = function(dateStr) {
        currentModalDate = dateStr;
        const [year, month, day] = dateStr.split('-');
        const dateObj = new Date(year, month - 1, day);
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        document.getElementById('modal_date_title').textContent = 
            `${dayNames[dateObj.getDay()]}, ${day} ${monthNames[month - 1]} ${year}`;

        document.getElementById('modal_field_filter').value = selectedFieldId;
        document.getElementById('modal_user_filter').value = selectedUserId;

        renderTimeSlots(dateStr);
        const modal = new bootstrap.Modal(document.getElementById('timeSlotsModal'));
        modal.show();
    };

    function renderTimeSlots(dateStr) {
        const bookings = bookingsData[dateStr] || [];
        const modalFieldFilter = document.getElementById('modal_field_filter').value;
        const modalUserFilter = document.getElementById('modal_user_filter').value;
        
        let filteredBookings = bookings;
        if (modalFieldFilter) filteredBookings = filteredBookings.filter(b => b.field_id == modalFieldFilter);
        if (modalUserFilter) filteredBookings = filteredBookings.filter(b => b.user_id == modalUserFilter);

        let slotsHtml = '';
        const operatingHours = { start: 8, end: 22 };
        
        for (let hour = operatingHours.start; hour < operatingHours.end; hour++) {
            const timeStr = `${String(hour).padStart(2, '0')}:00`;
            const nextHour = `${String(hour + 1).padStart(2, '0')}:00`;
            
            const bookedSlots = filteredBookings.filter(b => {
                const startHour = parseInt(b.start_time.split(':')[0]);
                const endHour = parseInt(b.end_time.split(':')[0]);
                return hour >= startHour && hour < endHour;
            });

            if (bookedSlots.length > 0) {
                bookedSlots.forEach(bookedSlot => {
                    let statusClass = 'slot-booked';
                    let statusBadge = 'bg-danger-subtle text-danger border-danger-subtle';
                    let statusText = 'Booked';
                    
                    if (bookedSlot.status === 'pending') {
                        statusClass = 'slot-pending';
                        statusBadge = 'bg-info-subtle text-info border-info-subtle';
                        statusText = 'Pending';
                    } else if (bookedSlot.status === 'confirmed') {
                        statusClass = 'slot-confirmed';
                        statusBadge = 'bg-primary-subtle text-primary border-primary-subtle';
                        statusText = 'Confirmed';
                    } else if (bookedSlot.status === 'completed') {
                        statusBadge = 'bg-success-subtle text-success border-success-subtle';
                        statusText = 'Selesai';
                    }

                    slotsHtml += `
                        <div class="time-slot-item ${statusClass}">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white p-2 rounded-circle shadow-sm border text-primary">
                                        <i data-lucide="clock" style="width: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-heading">${bookedSlot.start_time} - ${bookedSlot.end_time}</h6>
                                        <div class="d-flex gap-2 mt-1">
                                            <span class="small text-muted d-flex align-items-center gap-1">
                                                <i data-lucide="user" style="width: 10px"></i> ${bookedSlot.user_name}
                                            </span>
                                            <span class="small text-muted d-flex align-items-center gap-1">
                                                <i data-lucide="map-pin" style="width: 10px"></i> ${bookedSlot.field_name}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge ${statusBadge} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">${statusText}</span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                            <i data-lucide="more-vertical" style="width: 16px"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item small d-flex align-items-center gap-2" href="{{ url('admin/bookings') }}/${bookedSlot.id}/edit">
                                                <i data-lucide="edit-3" style="width: 14px"></i> Edit
                                            </a></li>
                                            <li><a class="dropdown-item small d-flex align-items-center gap-2 text-primary" href="{{ url('admin/bookings') }}/${bookedSlot.id}">
                                                <i data-lucide="eye" style="width: 14px"></i> Detail
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                slotsHtml += `
                    <div class="time-slot-item slot-available border-0 bg-light bg-opacity-25 border-dashed" style="border: 2px dashed #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-muted p-2">
                                    <i data-lucide="calendar-plus" style="width: 18px;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-muted">${timeStr} - ${nextHour}</h6>
                                    <span class="small text-muted opacity-75">Slot tersedia untuk booking</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.bookings.create') }}?date=${dateStr}&time=${timeStr}${modalFieldFilter ? '&field_id=' + modalFieldFilter : ''}" 
                               class="btn-capsule btn-capsule-light bg-white border px-4 py-2 small fw-bold">
                                Booking <i data-lucide="arrow-right" class="ms-1" style="width: 14px"></i>
                            </a>
                        </div>
                    </div>
                `;
            }
        }

        document.getElementById('time_slots_container').innerHTML = slotsHtml;
        document.getElementById('time_slots_container').classList.remove('d-none');
        document.getElementById('time_slots_loading').classList.add('d-none');
        if (window.lucide) lucide.createIcons();
    }

    // Initial load
    loadCalendar();
});
</script>
@endsection
