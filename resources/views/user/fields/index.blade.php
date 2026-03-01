@extends('layouts.app')

@section('title', 'Daftar Lapangan')
@section('page_title', 'Pilih Lapangan')

@section('content')
<div class="py-2">
    <!-- Header Section -->
    <div class="row g-3 align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold mb-1">Lapangan Tersedia</h4>
            <p class="text-muted small mb-0">Temukan lapangan futsal yang anda inginkan.</p>
        </div>
        <div class="col-md-6">
            <div class="position-relative">
                <i data-lucide="search" class="position-absolute translate-middle-y top-50 ms-3 text-muted" style="width: 18px;"></i>
                <input type="text" id="searchField" class="input-modern ps-5 py-2" placeholder="Cari nama lapangan...">
            </div>
        </div>
    </div>

    @if ($fields->isEmpty())
        <div class="card-modern p-5 text-center">
            <div class="p-4 bg-light rounded-circle d-inline-flex mb-4">
                <i data-lucide="inbox" class="text-muted" style="width: 40px; height: 40px;"></i>
            </div>
            <h5 class="fw-bold">Belum Ada Lapangan</h5>
            <p class="text-muted mb-0">Maaf, saat ini belum ada lapangan yang terdaftar di sistem.</p>
        </div>
    @else
        <div class="row g-4" id="fieldsGrid">
            @foreach ($fields as $field)
                <div class="col-12 col-md-6 col-xl-4 field-card-wrapper animate-slide-up" 
                     data-field-name="{{ $field->name ?? '' }}"
                     data-search-content="{{ strtolower($field->name ?? '') }} {{ strtolower($field->description ?? '') }} {{ (int)$field->price_per_hour }} {{ number_format($field->price_per_hour, 0, '', '') }}"
                     style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card-modern overflow-hidden h-100 flex-column d-flex">
                        <!-- Image Area -->
                        <div class="position-relative" style="height: 200px;">
                            @if($field->image)
                                <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i data-lucide="image" class="text-muted opacity-50" style="width: 48px; height: 48px;"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $field->name }}</h5>
                            <p class="text-muted small mb-4 flex-grow-1">
                                {{ $field->description ?? 'Lapangan futsal profesional dengan fasilitas standar nasional untuk kenyamanan bermain Anda.' }}
                            </p>

                            <!-- Features -->
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                <div class="badge bg-light text-muted fw-normal px-2 py-1 rounded-sm border d-flex align-items-center gap-1">
                                    <i data-lucide="users" style="width: 12px;"></i> 12 Pemain
                                </div>
                                <div class="badge bg-light text-muted fw-normal px-2 py-1 rounded-sm border d-flex align-items-center gap-1">
                                    <i data-lucide="layers" style="width: 12px;"></i> Sintetis
                                </div>
                            </div>

                            <!-- CTA Section -->
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between pt-3 border-top mt-auto gap-3">
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">Mulai dari</div>
                                    <div class="fw-bold text-success fs-5">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}<span class="text-muted fw-normal small">/jam</span></div>
                                </div>
                                <a href="{{ route('user.bookings.create', ['field_id' => $field->id]) }}" 
                                   class="btn-modern btn-modern-primary px-4 py-2 w-100 w-sm-auto">
                                    Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="card-modern p-5 text-center d-none">
            <div class="p-4 bg-light rounded-circle d-inline-flex mb-4">
                <i data-lucide="search-x" class="text-muted" style="width: 40px; height: 40px;"></i>
            </div>
            <h5 class="fw-bold">Tidak Menemukan Hasil</h5>
            <p class="text-muted mb-0">Coba gunakan kata kunci lain untuk mencari lapangan.</p>
        </div>
    @endif
</div>

<style>
.object-fit-cover { object-fit: cover; }
.rounded-md { border-radius: var(--radius-md); }
.rounded-sm { border-radius: var(--radius-sm); }

/* Search Animation */
.field-card-wrapper {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 1;
    transform: translateY(0) scale(1);
}

.field-card-wrapper.search-hide {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
    pointer-events: none;
}

.field-card-wrapper.search-show {
    animation: searchFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes searchFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Search input animation */
#searchField {
    transition: all 0.3s ease;
}

#searchField:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    transform: scale(1.01);
}

/* No results animation */
#noResults {
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (min-width: 576px) {
    .w-sm-auto { width: auto !important; }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchField');
    const noResults = document.getElementById('noResults');
    const fieldsGrid = document.getElementById('fieldsGrid');

    if (searchInput) {
        let searchTimeout;
        
        const performSearch = function() {
            const val = (searchInput.value || '').toLowerCase().trim();
            const wrappers = document.querySelectorAll('.field-card-wrapper');
            let found = 0;
            let delay = 0;
            
            wrappers.forEach((w, index) => {
                const fieldName = (w.getAttribute('data-field-name') || '').toLowerCase().trim();
                const content = (w.getAttribute('data-search-content') || '').toLowerCase();
                
                let matches = false;
                
                if (val === '') {
                    matches = true;
                } else if (fieldName.includes(val) || content.includes(val)) {
                    matches = true;
                }
                
                if (matches) {
                    // Show with animation
                    setTimeout(() => {
                        w.classList.remove('search-hide', 'd-none');
                        w.classList.add('search-show');
                        w.style.display = '';
                    }, delay);
                    delay += 50; // Stagger animation
                    found++;
                } else {
                    // Hide with animation
                    w.classList.remove('search-show');
                    w.classList.add('search-hide');
                    setTimeout(() => {
                        if (w.classList.contains('search-hide')) {
                            w.classList.add('d-none');
                        }
                    }, 300);
                }
            });

            if (noResults) {
                if (found === 0 && val !== '') {
                    setTimeout(() => {
                        noResults.classList.remove('d-none');
                        if (fieldsGrid) fieldsGrid.classList.add('d-none');
                    }, 350);
                } else {
                    noResults.classList.add('d-none');
                    if (fieldsGrid) fieldsGrid.classList.remove('d-none');
                }
            }
        };

        // Debounce search for smoother animation
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 150);
        });
    }

    if (window.lucide) lucide.createIcons();
});
</script>
@endpush
@endsection