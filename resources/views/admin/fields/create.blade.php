@extends('layouts.admin')

@section('title', 'Tambah Lapangan')
@section('page_title', 'Tambah Lapangan Baru')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <div class="row g-4">
        <!-- FORM COLUMN -->
        <div class="col-12 col-xl-8">
            <div class="card-modern border-0 shadow-sm overflow-hidden">
                <div class="p-4 border-bottom bg-light">
                    <h5 class="fw-bold mb-0 text-heading d-flex align-items-center gap-2">
                        <i data-lucide="map-pin" class="text-primary"></i>
                        Data Lapangan Baru
                    </h5>
                </div>
                
                <div class="p-4 p-md-5">
                    <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data" id="fieldForm">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Nama Lapangan -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Nama Lapangan</label>
                                <div class="position-relative">
                                    <i data-lucide="type" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="name" id="fieldNameInput" 
                                           class="input-modern ps-5 @error('name') is-invalid @enderror" 
                                           placeholder="Contoh: Lapangan A (Sintetis)" 
                                           value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Harga per Jam</label>
                                <div class="position-relative">
                                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 fw-bold text-success">Rp</span>
                                    <input type="number" name="price_per_hour" id="fieldPriceInput" 
                                           class="input-modern ps-5 fw-bold text-success @error('price_per_hour') is-invalid @enderror" 
                                           style="background-color: #f0fdf4;"
                                           placeholder="150000" 
                                           value="{{ old('price_per_hour') }}" required>
                                </div>
                                @error('price_per_hour')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Tipe Lapangan (Opsional)</label>
                                <div class="position-relative">
                                    <i data-lucide="layers" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" id="fieldTypeInput" 
                                           class="input-modern ps-5" 
                                           placeholder="Sintetis / Vinyl / Semen">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Deskripsi</label>
                                <textarea name="description" id="fieldDescInput" 
                                          class="input-modern @error('description') is-invalid @enderror" 
                                          rows="4" placeholder="Jelaskan fasilitas dan kondisi lapangan...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload Image Area -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Foto Lapangan</label>
                                <div class="image-upload-zone p-4 text-center border-dashed rounded-4 bg-light position-relative overflow-hidden transition-base">
                                    <input type="file" name="image" id="fieldImageInput" 
                                           class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer z-index-2" 
                                           accept="image/*">
                                    
                                    <div id="uploadPlaceholder" class="py-3">
                                        <div class="bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                            <i data-lucide="image-plus" class="text-primary" style="width: 28px; height: 28px;"></i>
                                        </div>
                                        <h6 class="fw-bold text-heading mb-1">Pilih Foto Lapangan</h6>
                                        <p class="text-muted small mb-0">Klik atau tarik gambar ke sini</p>
                                        <p class="text-muted opacity-50 mb-0" style="font-size: 0.7rem;">PNG, JPG atau WebP (Maks. 2MB)</p>
                                    </div>
                                    
                                    <div id="uploadPreview" class="d-none">
                                        <div class="position-relative d-inline-block">
                                            <img src="#" alt="Preview" class="rounded-3 shadow-sm border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                            <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-50 text-white small">
                                                Klik untuk mengganti gambar
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-4 pt-4 border-top">
                            <div class="col-12 col-md-auto order-1 order-md-2">
                                <button type="submit" class="btn-capsule btn-capsule-primary w-100 px-md-5 py-2 py-md-3">
                                    <i data-lucide="plus-circle" class="me-2"></i> Simpan Data Lapangan
                                </button>
                            </div>
                            <div class="col-12 col-md-auto order-2 order-md-1 ms-md-auto">
                                <a href="{{ route('admin.fields.index') }}" class="btn-capsule btn-capsule-light border w-100 px-md-5 py-2 py-md-3 text-center">
                                    <i data-lucide="x-circle" class="me-2"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PREVIEW COLUMN -->
        <div class="col-12 col-xl-4">
            <div class="sticky-xl-top" style="top: 2rem; z-index: 10;">
                <h6 class="fw-bold text-heading mb-3 d-flex align-items-center gap-2">
                    <i data-lucide="eye" class="text-primary" style="width: 18px;"></i>
                    Live Preview
                </h6>
                
                <div class="card-modern overflow-hidden border-0 shadow-lg animate-fade-in">
                    <!-- Preview Image -->
                    <div class="position-relative preview-img-container" style="background-color: #f1f5f9;">
                        <img id="cardPreviewImg" src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1000&auto=format&fit=crop" 
                             class="w-100 h-100 object-fit-cover opacity-50" alt="Field Preview">
                        <div id="imgOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <i data-lucide="image" class="text-muted opacity-30" style="width: 48px; height: 48px;"></i>
                        </div>
                        <div class="position-absolute badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill fw-bold" style="top: 15px; right: 15px; font-size: 0.7rem;">
                            TERSEDIA
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0 text-heading" id="previewTitle">Nama Lapangan</h5>
                            <span class="text-primary" id="previewType">Tipe</span>
                        </div>
                        
                        <p class="text-muted small mb-4" id="previewDesc">Deskripsi akan muncul di sini saat Anda mengetik...</p>

                        <!-- Price Section -->
                        <div class="p-3 rounded-4 bg-success-subtle border border-success-subtle d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted fs-xs text-uppercase fw-bold mb-0" style="font-size: 0.6rem; letter-spacing: 0.5px;">Harga Sewa</div>
                                <div class="fw-bold text-success fs-5">
                                    Rp <span id="previewPrice">0</span><span class="text-muted fw-normal small">/jam</span>
                                </div>
                            </div>
                            <div class="icon-box bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                <i data-lucide="tag" style="width: 18px;"></i>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary w-100 rounded-pill mt-4 disabled" style="opacity: 0.7;">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
                
                <div class="alert-modern mt-4">
                    <div class="d-flex gap-2">
                        <i data-lucide="info" class="text-primary mt-1" style="width: 18px; min-width: 18px;"></i>
                        <p class="mb-0 small">
                            <strong>Tips:</strong> Gunakan foto berkualitas tinggi agar pelanggan lebih tertarik untuk menyewa lapangan ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border: 2px dashed #cbd5e1 !important; }
    .z-index-2 { z-index: 2; }
    .object-fit-cover { object-fit: cover; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .border-success-subtle { border-color: #dcfce7 !important; }
    
    .image-upload-zone:hover {
        border-color: var(--primary) !important;
        background-color: #f8fafc !important;
    }

    .image-upload-zone:hover #uploadPlaceholder i {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .preview-img-container {
        height: 180px;
    }

    @media (max-width: 1200px) {
        .sticky-xl-top {
            position: relative !important;
            top: 0 !important;
        }
    }

    @media (max-width: 768px) {
        .preview-img-container {
            height: 150px;
        }
        .image-upload-zone {
            padding: 1.5rem !important;
        }
        #uploadPlaceholder i {
            width: 24px !important;
            height: 24px !important;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('fieldNameInput');
    const priceInput = document.getElementById('fieldPriceInput');
    const typeInput = document.getElementById('fieldTypeInput');
    const descInput = document.getElementById('fieldDescInput');
    const imgInput = document.getElementById('fieldImageInput');
    
    const previewTitle = document.getElementById('previewTitle');
    const previewPrice = document.getElementById('previewPrice');
    const previewType = document.getElementById('previewType');
    const previewDesc = document.getElementById('previewDesc');
    const previewImg = document.getElementById('cardPreviewImg');
    const imgOverlay = document.getElementById('imgOverlay');
    
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    const uploadPreviewImg = uploadPreview.querySelector('img');

    // Helper functions
    const formatNumber = (num) => {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    // Update functions
    function updatePreview() {
        previewTitle.textContent = nameInput.value || 'Nama Lapangan';
        previewPrice.textContent = priceInput.value ? formatNumber(priceInput.value) : '0';
        previewType.textContent = typeInput.value || 'Futsal';
        
        if (descInput.value) {
            previewDesc.textContent = descInput.value.length > 80 
                ? descInput.value.substring(0, 80) + '...' 
                : descInput.value;
        } else {
            previewDesc.textContent = 'Deskripsi akan muncul di sini saat Anda mengetik...';
        }
    }

    // Event listeners
    nameInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);
    typeInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);

    imgInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Main card preview
                previewImg.src = e.target.result;
                previewImg.classList.remove('opacity-50');
                if (imgOverlay) imgOverlay.classList.add('d-none');
                
                // Form upload zone preview
                uploadPreviewImg.src = e.target.result;
                uploadPlaceholder.classList.add('d-none');
                uploadPreview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Initialize
    updatePreview();
});
</script>
@endsection
