@extends('layouts.app')

@section('title', 'Jadwal Booking')
@section('page_title', 'Jadwal')

@section('content')
<div class="calendar-page py-2">
    <!-- Filters -->
    <div class="card-modern p-2 p-md-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.7rem;">LAPANGAN</label>
                <select id="field_filter" class="input-modern" style="height: 38px; font-size: 0.85rem;">
                    <option value="">Semua Lapangan</option>
                    @foreach($fields as $field)
                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 col-sm-4 col-lg-5">
                <label class="form-label small fw-bold text-muted mb-1 d-block" style="font-size: 0.7rem;">PERIODE</label>
                <div class="d-flex align-items-center gap-1">
                    <button id="prev_month" class="btn-modern btn-modern-light flex-shrink-0 p-0 d-flex align-items-center justify-content-center" style="height: 38px; width: 36px;">
                        <i data-lucide="chevron-left" style="width: 16px;"></i>
                    </button>
                    <input type="month" id="month_picker" class="input-modern text-center flex-grow-1" value="{{ now()->format('Y-m') }}" style="height: 38px; font-size: 0.85rem; min-width: 0;">
                    <button id="next_month" class="btn-modern btn-modern-light flex-shrink-0 p-0 d-flex align-items-center justify-content-center" style="height: 38px; width: 36px;">
                        <i data-lucide="chevron-right" style="width: 16px;"></i>
                    </button>
                </div>
            </div>
            <div class="col-4 col-sm-2 col-lg-3">
                <button id="refresh_calendar" class="btn-modern btn-modern-light w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px; font-size: 0.8rem;">
                    <i data-lucide="rotate-cw" style="width: 14px;"></i>
                    <span class="d-none d-sm-inline">Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="card-modern overflow-hidden">
        <div class="calendar-header p-2 p-md-3 text-white d-flex justify-content-between align-items-center gap-2" style="background: var(--primary);">
            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2 flex-shrink-0">
                <i data-lucide="calendar" class="d-none d-md-inline" style="width: 18px; height: 18px;"></i>
                <span id="current_month_display" style="font-size: 0.9rem;">Memuat...</span>
            </h5>
            <div class="d-flex gap-2 flex-shrink-0" style="font-size: 0.7rem;">
                <div class="d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 8px; height: 8px; background: #10b981;"></span> <span class="d-none d-sm-inline" style="font-size: 0.65rem;">Kosong</span></div>
                <div class="d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 8px; height: 8px; background: #f59e0b;"></span> <span class="d-none d-sm-inline" style="font-size: 0.65rem;">Tersedia</span></div>
                <div class="d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 8px; height: 8px; background: #ef4444;"></span> <span class="d-none d-sm-inline" style="font-size: 0.65rem;">Penuh</span></div>
            </div>
        </div>
        
        <div id="loading_calendar" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small fw-medium">Singkronisasi jadwal...</p>
        </div>

        <div id="calendar_container" class="d-none">
            <div class="calendar-scroll">
                <table class="table table-bordered mb-0" id="calendar_table">
                    <thead>
                        <tr>
                            <th>MG</th>
                            <th>SN</th>
                            <th>SL</th>
                            <th>RB</th>
                            <th>KM</th>
                            <th>JM</th>
                            <th>SB</th>
                        </tr>
                    </thead>
                    <tbody id="calendar_body">
                        <!-- JS Generated -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Slots -->
    <div class="modal fade" id="timeSlotsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 500px;">
            <div class="modal-content card-modern p-0 border-0">
                <div class="modal-header border-bottom p-3">
                    <div>
                        <h6 class="fw-bold mb-1" id="modal_date_title">Detail Jadwal</h6>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Klik jam yang tersedia untuk memesan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted" style="font-size: 0.7rem;">FILTER LAPANGAN</label>
                        <select class="input-modern w-100" id="modal_field_filter" style="height: 38px; font-size: 0.85rem;">
                            <option value="">Semua Lapangan</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="time_slots_loading" class="text-center py-4">
                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                    </div>
                    <div id="time_slots_container" class="d-none d-grid gap-2">
                        <!-- JS Generated -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Calendar Page Container */
.calendar-page {
    max-width: 100%;
    overflow-x: hidden;
}

/* Calendar Container */
.calendar-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.calendar-header {
    background: var(--primary) !important;
}

/* Calendar Table Base */
#calendar_table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

#calendar_table th,
#calendar_table td {
    width: calc(100% / 7);
    text-align: center;
    vertical-align: top;
}

#calendar_table th {
    padding: 12px 4px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    background: var(--background);
}

#calendar_table td {
    height: 100px;
    padding: 8px;
    cursor: pointer;
    transition: var(--transition-base);
    border: 1px solid var(--border-light);
    background: white;
}

#calendar_table td:hover:not(.past-date):not(.empty-cell) {
    background-color: var(--primary-light);
}

#calendar_table td.past-date {
    background-color: #f8fafc;
    cursor: not-allowed;
    opacity: 0.6;
}

#calendar_table td.empty-cell {
    background-color: var(--background);
    cursor: default;
}

.date-number {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 4px;
    color: var(--text-main);
}

.status-indicator {
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.6rem;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-block;
    white-space: nowrap;
}

.booking-count {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--primary);
    margin-top: 4px;
}

/* Slot Items in Modal */
.slot-item {
    padding: 1rem;
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: var(--transition-base);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.slot-item-available:hover {
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: var(--shadow-sm);
}

.slot-item-booked {
    background-color: var(--background);
    border-color: var(--border-light);
    opacity: 0.7;
}

/* ========== RESPONSIVE STYLES ========== */

/* Tablet */
@media (max-width: 992px) {
    #calendar_table td {
        height: 85px;
        padding: 6px;
    }
    .date-number {
        font-size: 0.9rem;
    }
    .status-indicator {
        font-size: 0.55rem;
        padding: 2px 4px;
    }
}

/* Phone Landscape / Small Tablet */
@media (max-width: 768px) {
    .calendar-header {
        padding: 0.75rem !important;
        flex-direction: row !important;
    }
    
    .calendar-header h5 {
        font-size: 0.9rem !important;
    }
    
    #calendar_table th {
        padding: 8px 2px;
        font-size: 0.65rem;
    }
    
    #calendar_table td {
        height: 70px;
        padding: 4px 2px;
    }
    
    .date-number {
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    
    .status-indicator {
        font-size: 0.5rem;
        padding: 1px 3px;
    }
    
    .booking-count {
        font-size: 0.6rem;
        margin-top: 2px;
    }
    
    /* Modal adjustments */
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .modal-content .modal-header,
    .modal-content .modal-body {
        padding: 1rem !important;
    }
    
    .slot-item {
        padding: 0.75rem;
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}

/* Phone Portrait */
@media (max-width: 576px) {
    .px-0.py-2 {
        padding: 0.25rem !important;
    }
    
    .card-modern.p-3 {
        padding: 0.75rem !important;
    }
    
    .calendar-header {
        padding: 0.5rem 0.75rem !important;
    }
    
    .calendar-header h5 {
        font-size: 0.85rem !important;
    }
    
    .calendar-header .d-flex.gap-3 {
        gap: 0.4rem !important;
    }
    
    .calendar-header .rounded-circle {
        width: 8px !important;
        height: 8px !important;
    }
    
    #calendar_table th {
        padding: 6px 1px;
        font-size: 0.55rem;
        letter-spacing: -0.5px;
    }
    
    #calendar_table td {
        height: 58px;
        padding: 3px 1px;
    }
    
    .date-number {
        font-size: 0.75rem;
        margin-bottom: 1px;
    }
    
    .status-indicator {
        font-size: 0.4rem;
        padding: 1px 2px;
        letter-spacing: -0.3px;
    }
    
    .booking-count {
        display: none;
    }
}

/* Very Small Phone (iPhone SE, etc) */
@media (max-width: 375px) {
    #calendar_table th {
        font-size: 0.5rem;
        padding: 4px 0;
    }
    
    #calendar_table td {
        height: 52px;
        padding: 2px 1px;
    }
    
    .date-number {
        font-size: 0.7rem;
    }
    
    .status-indicator {
        font-size: 0.35rem;
        padding: 1px 2px;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentMonth = new Date().getMonth() + 1;
    let currentYear = new Date().getFullYear();
    let bookingsData = {};
    let selectedFieldId = '';
    let currentModalDate = '';

    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    document.getElementById('field_filter').addEventListener('change', function() {
        selectedFieldId = this.value;
        loadCalendar();
    });

    document.getElementById('month_picker').addEventListener('change', function() {
        const [year, month] = this.value.split('-');
        currentYear = parseInt(year);
        currentMonth = parseInt(month);
        loadCalendar();
    });

    document.getElementById('prev_month').addEventListener('click', function() {
        if (currentMonth === 1) { currentMonth = 12; currentYear--; } else { currentMonth--; }
        updateMonthPicker(); loadCalendar();
    });

    document.getElementById('next_month').addEventListener('click', function() {
        if (currentMonth === 12) { currentMonth = 1; currentYear++; } else { currentMonth++; }
        updateMonthPicker(); loadCalendar();
    });

    document.getElementById('refresh_calendar').addEventListener('click', loadCalendar);
    document.getElementById('modal_field_filter').addEventListener('change', () => renderTimeSlots(currentModalDate));

    function updateMonthPicker() {
        document.getElementById('month_picker').value = `${currentYear}-${currentMonth.toString().padStart(2, '0')}`;
    }

    function loadCalendar() {
        document.getElementById('loading_calendar').classList.remove('d-none');
        document.getElementById('calendar_container').classList.add('d-none');

        let url;
        try {
            const routeUrl = '{{ route("user.bookings.calendar.data") }}';
            url = new URL(routeUrl, window.location.origin);
            url.searchParams.append('month', currentMonth);
            url.searchParams.append('year', currentYear);
            if (selectedFieldId) url.searchParams.append('field_id', selectedFieldId);
        } catch (e) {
            console.error('Invalid URL:', e);
            document.getElementById('loading_calendar').classList.add('d-none');
            return;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bookingsData = data.data;
                    renderCalendar();
                } else {
                    console.error('Data error:', data);
                }
            })
            .catch(err => console.error('Fetch error:', err))
            .finally(() => {
                document.getElementById('loading_calendar').classList.add('d-none');
                document.getElementById('calendar_container').classList.remove('d-none');
                if (window.lucide) lucide.createIcons();
            });
    }

    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const today = new Date(); today.setHours(0,0,0,0);

        document.getElementById('current_month_display').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        let html = '<tr>';
        const startDay = firstDay;
        for (let i = 0; i < startDay; i++) html += '<td class="empty-cell"></td>';

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isPast = new Date(currentYear, currentMonth - 1, day) < today;
            const bookings = bookingsData[dateStr] || [];
            
            let status = isPast ? 'Lewat' : (bookings.length === 0 ? 'Kosong' : (bookings.length < 5 ? 'Tersedia' : 'Penuh'));
            let colorClass = isPast ? 'bg-secondary' : (status === 'Kosong' ? 'bg-success' : (status === 'Tersedia' ? 'bg-warning' : 'bg-danger'));
            
            html += `
                <td class="${isPast ? 'past-date' : ''}" ${!isPast ? `onclick="showTimeSlots('${dateStr}')"` : ''}>
                    <div class="date-number">${day}</div>
                    <span class="status-indicator ${colorClass} text-white">${status}</span>
                    ${bookings.length > 0 ? `<div class="booking-count">${bookings.length} Booking</div>` : ''}
                </td>
            `;

            if ((startDay + day) % 7 === 0 && day !== daysInMonth) html += '</tr><tr>';
        }

        const totalCellsUsed = startDay + daysInMonth;
        const remainingCells = (7 - (totalCellsUsed % 7)) % 7;
        for (let i = 0; i < remainingCells; i++) {
            html += '<td class="empty-cell"></td>';
        }

        document.getElementById('calendar_body').innerHTML = html + '</tr>';
    }

    window.showTimeSlots = function(dateStr) {
        currentModalDate = dateStr;
        const [y, m, d] = dateStr.split('-');
        const dateObj = new Date(y, m-1, d);
        document.getElementById('modal_date_title').textContent = `${['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][dateObj.getDay()]}, ${d} ${monthNames[m-1]} ${y}`;
        document.getElementById('modal_field_filter').value = selectedFieldId;
        renderTimeSlots(dateStr);
        new bootstrap.Modal(document.getElementById('timeSlotsModal')).show();
    };

    function renderTimeSlots(dateStr) {
        const bookings = bookingsData[dateStr] || [];
        const f = document.getElementById('modal_field_filter').value;
        const filtered = f ? bookings.filter(b => b.field_id == f) : bookings;
        
        let html = '';
        for (let h = 8; h < 22; h++) {
            const t = `${String(h).padStart(2, '0')}:00`;
            const booked = filtered.filter(b => h >= parseInt(b.start_time) && h < parseInt(b.end_time));

            if (booked.length > 0) {
                booked.forEach(b => {
                    html += `<div class="slot-item slot-item-booked border-0 bg-light">
                        <div>
                            <div class="fw-bold text-muted small"><i data-lucide="clock" style="width:12px"></i> ${b.start_time} - ${b.end_time}</div>
                            <div class="small text-muted">${b.field_name}</div>
                        </div>
                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border-0 small px-3">Sudah Dipesan</span>
                    </div>`;
                });
            } else {
                const bId = f || selectedFieldId || '';
                const url = `{{ route('user.bookings.create') }}?date=${dateStr}&time=${t}${bId ? '&field_id='+bId : ''}`;
                html += `<div class="slot-item slot-item-available">
                    <div>
                        <div class="fw-bold text-primary"><i data-lucide="clock" style="width:14px"></i> ${t} - ${String(h+1).padStart(2, '0')}:00</div>
                        <div class="small text-muted">${f ? 'Lapangan Tersedia' : 'Slot Terbuka'}</div>
                    </div>
                    <a href="${url}" class="btn-modern btn-modern-primary py-1 px-4 small">
                        Pilih
                    </a>
                </div>`;
            }
        }
        document.getElementById('time_slots_container').innerHTML = html;
        document.getElementById('time_slots_container').classList.remove('d-none');
        document.getElementById('time_slots_loading').classList.add('d-none');
        if (window.lucide) lucide.createIcons();
    }

    loadCalendar();
});
</script>
@endpush