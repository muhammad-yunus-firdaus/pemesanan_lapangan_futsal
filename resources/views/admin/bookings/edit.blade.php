@extends('layouts.admin')

@section('title', 'Edit Pemesanan')
@section('page_title', 'Edit Data Pemesanan')

@section('content')
<div class="container-fluid px-2 px-md-3 my-4">
    <div class="row g-4">
        <!-- FORM SECTION -->
        <div class="col-lg-8">
            <div class="card-modern border-0 shadow-sm h-100">
                <div class="p-4 border-bottom bg-light">
                    <h5 class="fw-bold mb-0 text-heading d-flex align-items-center gap-2">
                        <i data-lucide="edit" class="text-primary"></i>
                        Edit Data Pemesanan
                    </h5>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" id="editBookingForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Pilih User -->
                            <div class="col-md-12">
                                <label for="user_id" class="form-label fw-bold text-heading small text-uppercase">Customer</label>
                                <div class="input-group-modern">
                                    <span class="input-group-icon"><i data-lucide="user"></i></span>
                                    <select class="form-select-modern @error('user_id') is-invalid @enderror" name="user_id" id="user_id" required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilih Lapangan -->
                            <div class="col-md-12">
                                <label for="field_id" class="form-label fw-bold text-heading small text-uppercase">Pilih Lapangan</label>
                                <div class="input-group-modern">
                                    <span class="input-group-icon"><i data-lucide="map-pin"></i></span>
                                    <select 
                                        class="form-select-modern @error('field_id') is-invalid @enderror" 
                                        name="field_id" 
                                        id="field_id" 
                                        required>
                                        @foreach ($fields as $field)
                                            <option value="{{ $field->id }}" 
                                                data-price="{{ $field->price_per_hour }}"
                                                data-description="{{ $field->description ?? 'Tidak ada deskripsi' }}"
                                                data-image="{{ $field->image ? asset('storage/' . $field->image) : asset('images/default-field.jpg') }}"
                                                {{ $booking->field_id == $field->id ? 'selected' : '' }}>
                                                {{ $field->name }} (Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('field_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Waktu Booking -->
                            <div class="col-md-6">
                                <label for="booking_time" class="form-label fw-bold text-heading small text-uppercase">Waktu Mulai</label>
                                <div class="input-group-modern">
                                    <span class="input-group-icon"><i data-lucide="calendar"></i></span>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control-modern @error('booking_time') is-invalid @enderror" 
                                        name="booking_time" 
                                        id="booking_time" 
                                        value="{{ \Carbon\Carbon::parse($booking->booking_time)->format('Y-m-d\TH:i') }}"
                                        required>
                                </div>
                                @error('booking_time')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Durasi -->
                            <div class="col-md-6">
                                <label for="duration" class="form-label fw-bold text-heading small text-uppercase">Durasi (Jam)</label>
                                <div class="input-group-modern">
                                    <span class="input-group-icon"><i data-lucide="clock"></i></span>
                                    <input 
                                        type="number" 
                                        class="form-control-modern @error('duration') is-invalid @enderror" 
                                        name="duration" 
                                        id="duration" 
                                        min="1" 
                                        max="24"
                                        value="{{ old('duration', $booking->duration) }}" 
                                        required>
                                </div>
                                @error('duration')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <label for="status" class="form-label fw-bold text-heading small text-uppercase">Status</label>
                                <div class="input-group-modern">
                                    <span class="input-group-icon"><i data-lucide="info"></i></span>
                                    <select class="form-select-modern @error('status') is-invalid @enderror" name="status" id="status" required>
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 pt-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-auto">
                                        <button type="submit" class="btn-capsule btn-capsule-primary w-100 px-md-4 py-2 py-md-3 border-0">
                                            <i data-lucide="save" style="width: 16px;"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-auto">
                                        <a href="{{ route('admin.bookings.index') }}" class="btn-capsule btn-capsule-light w-100 px-md-5 py-2 py-md-3 border text-center">
                                            <i data-lucide="x-circle" style="width: 16px;"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SUMMARY & PREVIEW COLUMN -->
        <div class="col-lg-4">
            <!-- FIELD PREVIEW -->
            <div id="field_preview_container" class="card-modern border-0 shadow-sm mb-4 overflow-hidden hover-lift">
                <img id="field_preview_image" src="{{ asset('storage/' . optional($booking->field)->image) }}" alt="Preview" class="card-img-top" style="height: 180px; object-fit: cover;">
                <div class="p-4">
                    <h6 class="fw-bold text-heading mb-2" id="field_preview_name_text">{{ optional($booking->field)->name }}</h6>
                    <p id="field_preview_description" class="text-muted small mb-3">{{ optional($booking->field)->description }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="small text-muted fw-bold">HARGA / JAM</span>
                        <span id="field_preview_price" class="fw-bold text-success fs-5">Rp {{ number_format(optional($booking->field)->price_per_hour, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- PRICE SUMMARY -->
            <div class="card-modern border-0 shadow-sm" style="background: linear-gradient(135deg, white 0%, var(--primary-light) 100%);">
                <div class="p-4 border-bottom bg-white bg-opacity-50">
                    <h6 class="fw-bold mb-0 text-heading d-flex align-items-center gap-2">
                        <i data-lucide="wallet" class="text-success"></i>
                        Ringkasan Biaya
                    </h6>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="fw-bold text-heading" id="summary_subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Durasi</span>
                        <span class="fw-bold text-heading" id="summary_duration">1 Jam</span>
                    </div>
                    <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-heading fs-6">TOTAL</span>
                        <div class="text-end">
                            <span class="fw-bold text-success fs-4" id="total_price_display">Rp 0</span>
                            <input type="hidden" name="total_price" id="total_price_hidden" form="editBookingForm" value="{{ $booking->total_price }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 alert-modern alert-modern-primary shadow-sm">
                <i data-lucide="info" style="width: 20px;"></i>
                <div>
                    <h6 class="fw-bold mb-1 small">Informasi Edit</h6>
                    <p class="mb-0 alert-text" style="font-size: 0.8rem;">Mengubah lapangan atau durasi akan memperbarui total harga secara otomatis.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    padding: 0.75rem 1rem 0.75rem 3rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    width: 100%;
    transition: var(--transition-base);
    font-size: 0.9rem;
    background: white;
}

.form-control-modern:focus, .form-select-modern:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-light);
    outline: none;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fieldDropdown = document.getElementById('field_id');
    const durationInput = document.getElementById('duration');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const totalPriceHidden = document.getElementById('total_price_hidden');
    const summarySubtotal = document.getElementById('summary_subtotal');
    const summaryDuration = document.getElementById('summary_duration');

    const previewContainer = document.getElementById('field_preview_container');
    const previewImage = document.getElementById('field_preview_image');
    const previewDescription = document.getElementById('field_preview_description');
    const previewPrice = document.getElementById('field_preview_price');
    const previewNameText = document.getElementById('field_preview_name_text');

    // Simpan data lapangan dalam object
    const fieldsData = {};
    Array.from(fieldDropdown.options).forEach(option => {
        if(option.value){
            fieldsData[option.value] = {
                name: option.text.split('(')[0].trim(),
                price: parseFloat(option.dataset.price) || 0,
                description: option.dataset.description || 'Tidak ada deskripsi',
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
            // Update preview
            previewImage.src = fieldsData[selectedId].image;
            previewNameText.textContent = fieldsData[selectedId].name;
            previewDescription.textContent = fieldsData[selectedId].description;
            previewPrice.textContent = formatRupiah(fieldsData[selectedId].price);

            // Hitung total harga
            const subtotal = fieldsData[selectedId].price;
            const totalPrice = subtotal * duration;
            
            summarySubtotal.textContent = formatRupiah(subtotal);
            summaryDuration.textContent = duration + ' Jam';
            totalPriceDisplay.textContent = formatRupiah(totalPrice);
            totalPriceHidden.value = totalPrice;
        }
    }

    // Event listeners
    fieldDropdown.addEventListener('change', updatePreviewAndTotal);
    durationInput.addEventListener('input', updatePreviewAndTotal);

    // Initial update
    updatePreviewAndTotal();

    // Re-init lucide
    lucide.createIcons();
});
</script>
@endsection
@endsection
