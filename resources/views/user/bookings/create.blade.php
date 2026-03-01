@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-3 py-4">
    <!-- Header -->
    <div class="card-modern p-4 mb-4 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, white 0%, var(--primary-light) 100%);">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary shadow-sm">
                <i data-lucide="calendar-plus" style="width: 24px;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-heading">Buat Pemesanan Baru</h4>
                <p class="mb-0 text-muted small">Lengkapi detail untuk mengamankan slot lapangan Anda.</p>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="modern-steps p-3 mb-4 d-none d-md-flex justify-content-center">
        <div class="d-flex align-items-center gap-4">
            <div class="step-item active">
                <span class="step-num">1</span>
                <span class="step-text">Detail Lapangan</span>
            </div>
            <div class="step-divider"></div>
            <div class="step-item" id="step2">
                <span class="step-num">2</span>
                <span class="step-text">Konfirmasi</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- FORM COLUMN -->
        <div class="col-lg-8">
            <div class="card-modern p-4 border-0 shadow-sm h-100">
                <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Field Selection -->
                        <div class="col-12">
                            <label class="form-label-modern mb-2 fw-bold text-heading small text-uppercase">Pilih Lapangan</label>
                            <div class="input-group-modern shadow-sm">
                                <span class="input-group-icon"><i data-lucide="map-pin"></i></span>
                                <select 
                                    class="form-select-modern @error('field_id') is-invalid @enderror" 
                                    name="field_id" 
                                    id="field_id" 
                                    required>
                                    <option value="">-- Pilih Lapangan --</option>
                                    @foreach ($fields as $field)
                                        <option value="{{ $field->id }}" 
                                            data-price="{{ $field->price_per_hour }}"
                                            data-description="{{ $field->description }}"
                                            data-image="{{ asset('storage/' . $field->image) }}"
                                            {{ old('field_id', request('field_id')) == $field->id ? 'selected' : '' }}>
                                            {{ $field->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('field_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date & Time -->
                        <div class="col-sm-6">
                            <label class="form-label-modern mb-2 fw-bold text-heading small text-uppercase">Waktu Main</label>
                            <div class="input-group-modern shadow-sm">
                                <span class="input-group-icon"><i data-lucide="clock"></i></span>
                                <input 
                                    type="datetime-local" 
                                    class="form-control-modern @error('booking_time') is-invalid @enderror" 
                                    name="booking_time" 
                                    id="booking_time" 
                                    value="{{ old('booking_time', request('date') && request('time') ? request('date') . 'T' . request('time') : '') }}" 
                                    required>
                            </div>
                            @error('booking_time')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div class="col-sm-6">
                            <label class="form-label-modern mb-2 fw-bold text-heading small text-uppercase">Durasi (Jam)</label>
                            <div class="input-group-modern shadow-sm">
                                <span class="input-group-icon"><i data-lucide="hourglass"></i></span>
                                <input 
                                    type="number" 
                                    class="form-control-modern @error('duration') is-invalid @enderror" 
                                    name="duration" 
                                    id="duration" 
                                    min="1" 
                                    max="12"
                                    value="{{ old('duration', 1) }}" 
                                    required>
                            </div>
                            @error('duration')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 pt-4">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="btn-capsule btn-capsule-primary flex-grow-1 py-3 border-0">
                                    <i data-lucide="send" style="width: 18px;"></i> Booking Lapangan Sekarang
                                </button>
                                <a href="{{ route('user.bookings.calendar') }}" class="btn-capsule btn-capsule-light px-4 py-3 border flex-grow-1 flex-md-grow-0 text-center">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SUMMARY COLUMN -->
        <div class="col-lg-4">
            <!-- Field Preview -->
            <div id="field_preview_container" class="card-modern border-0 shadow-sm mb-4 d-none overflow-hidden hover-lift">
                <img id="field_preview_image" src="#" alt="Preview" class="card-img-top" style="height: 180px; object-fit: cover;">
                <div class="p-4">
                    <h5 class="fw-bold text-heading mb-2" id="field_preview_name">Nama Lapangan</h5>
                    <p id="field_preview_description" class="text-muted small mb-3"></p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="small text-muted fw-bold">HARGA / JAM</span>
                        <span id="field_preview_price" class="fw-bold text-success fs-5">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="card-modern border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="p-4 border-bottom">
                    <h6 class="fw-bold mb-0 text-heading d-flex align-items-center gap-2">
                        <i data-lucide="wallet" class="text-success"></i>
                        Estimasi Biaya
                    </h6>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="fw-bold text-heading" id="summary_subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted small">Durasi Main</span>
                        <span class="fw-bold text-heading" id="summary_duration">1 Jam</span>
                    </div>
                    <div class="pt-4 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-heading fs-6">TOTAL BAYAR</span>
                        <div class="text-end">
                            <span class="fw-bold text-success fs-3" id="total_price_text">Rp 0</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-20 d-flex gap-2">
                        <i data-lucide="info" class="text-primary mt-1" style="width: 16px;"></i>
                        <p class="mb-0 text-heading fw-medium" style="font-size: 0.75rem;">Pembayaran dilakukan melalui admin di lokasi setelah booking dikonfirmasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Steps */
.modern-steps {
    background: white;
    border-radius: var(--radius-full);
}

.step-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    opacity: 0.5;
    transition: var(--transition-base);
}

.step-item.active {
    opacity: 1;
}

.step-num {
    width: 28px;
    height: 28px;
    background: var(--gray-200);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--gray-700);
}

.active .step-num {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 10px rgba(14, 165, 233, 0.4);
}

.step-text {
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text-heading);
}

.step-divider {
    width: 60px;
    height: 2px;
    background: var(--gray-200);
}

/* Modern Input Group */
.input-group-modern {
    position: relative;
    display: flex;
    align-items: center;
}

.input-group-icon {
    position: absolute;
    left: 1rem;
    color: var(--text-muted);
    z-index: 5;
    display: flex;
    align-items: center;
}

.input-group-icon i {
    width: 18px;
    height: 18px;
}

.form-control-modern, .form-select-modern {
    padding: 0.85rem 1rem 0.85rem 3rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    width: 100%;
    transition: var(--transition-base);
    font-size: 0.95rem;
    background: white;
}

.form-control-modern:focus, .form-select-modern:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-light);
    outline: none;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fieldDropdown = document.getElementById('field_id');
    const durationInput = document.getElementById('duration');
    const totalPriceText = document.getElementById('total_price_text');
    const summarySubtotal = document.getElementById('summary_subtotal');
    const summaryDuration = document.getElementById('summary_duration');

    const previewContainer = document.getElementById('field_preview_container');
    const previewImage = document.getElementById('field_preview_image');
    const previewDescription = document.getElementById('field_preview_description');
    const previewPrice = document.getElementById('field_preview_price');
    const previewName = document.getElementById('field_preview_name');

    const fieldsData = {};
    Array.from(fieldDropdown.options).forEach(option => {
        if(option.value){
            fieldsData[option.value] = {
                name: option.text.trim(),
                price: parseFloat(option.dataset.price) || 0,
                description: option.dataset.description || '',
                image: option.dataset.image || ''
            };
        }
    });

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function updatePreviewAndTotal() {
        const selectedId = fieldDropdown.value;
        const duration = parseInt(durationInput.value) || 0;

        if(selectedId && fieldsData[selectedId]){
            previewContainer.classList.remove('d-none');
            previewImage.src = fieldsData[selectedId].image;
            previewName.textContent = fieldsData[selectedId].name;
            previewDescription.textContent = fieldsData[selectedId].description;
            previewPrice.textContent = formatRupiah(fieldsData[selectedId].price);

            const subtotal = fieldsData[selectedId].price;
            const total = subtotal * duration;

            summarySubtotal.textContent = formatRupiah(subtotal);
            summaryDuration.textContent = duration + ' Jam';
            totalPriceText.textContent = formatRupiah(total);

            // Add active step logic
            if(duration > 0) {
                document.getElementById('step2').classList.add('active');
            }
        } else {
            previewContainer.classList.add('d-none');
            summarySubtotal.textContent = 'Rp 0';
            summaryDuration.textContent = '0 Jam';
            totalPriceText.textContent = 'Rp 0';
            document.getElementById('step2').classList.remove('active');
        }
    }

    fieldDropdown.addEventListener('change', updatePreviewAndTotal);
    durationInput.addEventListener('input', updatePreviewAndTotal);
    
    updatePreviewAndTotal();
});
</script>
@endpush
@endsection
