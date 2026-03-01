@extends('layouts.admin')

@section('title', 'Kelola Lapangan')
@section('page_title', 'Kelola Lapangan')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="container-fluid px-2 px-md-3">
    <!-- Header Section -->
    <div class="card-modern p-4 mb-4" style="border-radius: var(--radius-lg);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Daftar Lapangan Futsal</h4>
                <p class="mb-0 text-muted small">Kelola informasi, harga, dan ketersediaan lapangan.</p>
            </div>
            <a href="{{ route('admin.fields.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                <i data-lucide="plus" style="width: 18px;"></i> Tambah Lapangan
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

    @if ($fields->isEmpty())
        <div class="card-modern p-5 text-center">
            <div class="py-4">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i data-lucide="map-pin" class="text-muted" style="width: 32px; height: 32px;"></i>
                </div>
                <h5 class="text-heading fw-bold">Belum Ada Lapangan</h5>
                <p class="text-muted small mb-4">Belum ada data lapangan yang terdaftar di sistem.</p>
                <a href="{{ route('admin.fields.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                    <i data-lucide="plus" style="width: 18px;"></i> Tambah Lapangan Pertama
                </a>
            </div>
        </div>
    @else
        <div class="row g-4" id="fieldsGrid">
            @foreach ($fields as $field)
                <div class="col-12 col-md-6 col-xl-4 field-card-wrapper animate-slide-up" 
                     style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card-modern overflow-hidden h-100 flex-column d-flex">
                        <!-- Image Area -->
                        <div class="position-relative" style="height: 180px;">
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
                            <h5 class="fw-bold mb-1 text-heading">{{ $field->name }}</h5>
                            <p class="text-muted small mb-4 flex-grow-1 field-description">
                                {{ Str::limit($field->description, 80) ?? 'Lapangan futsal profesional dengan fasilitas terbaik.' }}
                            </p>

                            <!-- Price Section (UNIFIED GREEN) -->
                            <div class="price-container p-3 mb-4 rounded-3 bg-success-subtle border border-success-subtle">
                                <div class="text-muted fs-xs text-uppercase fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">Harga Sewa</div>
                                <div class="fw-bold text-success fs-5">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}<span class="text-muted fw-normal small">/jam</span></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3 border-top mt-auto">
                                <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn-action btn-action-edit flex-fill">
                                    <i data-lucide="edit-3" style="width: 14px;"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus lapangan ini?')" class="flex-fill">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete w-100">
                                        <i data-lucide="trash-2" style="width: 14px;"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .object-fit-cover { object-fit: cover; }
    
    .field-card-wrapper .card-modern {
        transition: var(--transition-base);
        border: 1px solid var(--border-light);
    }
    
    .field-card-wrapper .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
    }

    .bg-success-subtle { background-color: #f0fdf4; }
    .border-success-subtle { border-color: #dcfce7 !important; }

    .field-description {
        min-height: 40px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: var(--transition-base);
        border: none;
    }

    .btn-action-edit {
        background-color: var(--primary);
        color: white;
    }

    .btn-action-edit:hover {
        background-color: var(--primary-hover);
        color: white;
        transform: translateY(-2px);
    }

    .btn-action-delete {
        background-color: #fef2f2;
        color: var(--danger);
        border: 1px solid #fee2e2;
    }

    .btn-action-delete:hover {
        background-color: var(--danger);
        color: white;
        transform: translateY(-2px);
    }
</style>
@endsection
