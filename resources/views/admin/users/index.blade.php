@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page_title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <!-- Header Section -->
    <div class="card-modern p-4 mb-4" style="border-radius: var(--radius-lg);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-heading">Manajemen Pengguna</h4>
                <p class="mb-0 text-muted small">Kelola akun administrator dan pelanggan Lembang Arena.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                <i data-lucide="user-plus" style="width: 18px;"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card-modern p-3 mb-4 shadow-sm" style="border-radius: var(--radius-md);">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="position-relative">
                    <i data-lucide="search" class="position-absolute translate-middle-y top-50 ms-3 text-muted" style="width: 18px;"></i>
                    <input type="text" id="userSearch" class="input-modern ps-5 py-2" placeholder="Cari nama atau email...">
                </div>
            </div>
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

    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="alert-circle" style="width: 18px;"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($users->isEmpty())
        <div class="card-modern p-5 text-center">
            <div class="py-4">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i data-lucide="users" class="text-muted" style="width: 32px; height: 32px;"></i>
                </div>
                <h5 class="text-heading fw-bold">Belum Ada Pengguna</h5>
                <p class="text-muted small mb-4">Belum ada data pengguna yang terdaftar di sistem.</p>
                <a href="{{ route('admin.users.create') }}" class="btn-capsule btn-capsule-primary px-4 py-2">
                    <i data-lucide="user-plus" style="width: 18px;"></i> Tambah Pengguna Pertama
                </a>
            </div>
        </div>
    @else
        <!-- DESKTOP TABLE VIEW -->
        <div class="card-modern overflow-hidden d-none d-md-block shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">User</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Nomor Telepon</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Role</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Bergabung</th>
                            <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase" style="font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @foreach ($users as $user)
                        <tr class="user-row" data-search="{{ strtolower($user->name) }} {{ strtolower($user->username) }} {{ strtolower($user->phone_number) }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-light text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--border-light);">
                                        <i data-lucide="user" style="width: 18px;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-heading mb-0">{{ $user->name }}</div>
                                        <div class="text-muted small">@<span>{{ $user->username }}</span></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-lucide="phone" class="text-muted" style="width: 14px;"></i>
                                    <span class="text-heading fw-medium">{{ $user->phone_number ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2" style="font-size: 0.65rem; font-weight: 700;">ADMIN</span>
                                @else
                                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2" style="font-size: 0.65rem; font-weight: 700;">USER</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-heading small fw-medium">{{ $user->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-muted hover-primary transition-base rounded-3" title="Edit User">
                                        <i data-lucide="edit-3" style="width: 18px;"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-muted hover-danger transition-base rounded-3 border-0 bg-transparent" title="Hapus User">
                                            <i data-lucide="trash-2" style="width: 18px;"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE CARD VIEW -->
        <div class="d-block d-md-none" id="userCardList">
            @foreach ($users as $user)
            <div class="card-modern p-3 mb-3 user-card-mobile animate-fade-in" data-search="{{ strtolower($user->name) }} {{ strtolower($user->username) }} {{ strtolower($user->phone_number) }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-light text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-light);">
                            <i data-lucide="user" style="width: 20px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-heading mb-0">{{ $user->name }}</div>
                            <div class="text-muted small">@<span>{{ $user->username }}</span></div>
                        </div>
                    </div>
                    @if($user->role === 'admin')
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.6rem; font-weight: 700;">ADMIN</span>
                    @else
                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.6rem; font-weight: 700;">USER</span>
                    @endif
                </div>

                <div class="bg-light p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i data-lucide="phone" class="text-muted" style="width: 14px;"></i>
                        <span class="text-heading small fw-bold">{{ $user->phone_number ?? '-' }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="calendar" class="text-muted" style="width: 14px;"></i>
                        <span class="text-muted small">Bergabung: {{ $user->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-capsule btn-capsule-light border flex-grow-1 text-center text-heading fw-semibold py-2" style="font-size: 0.75rem;">
                        <i data-lucide="edit-3" class="me-1" style="width: 14px;"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Hapus pengguna?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-capsule btn-capsule-danger w-100 py-2 fw-semibold" style="font-size: 0.75rem;">
                            <i data-lucide="trash-2" class="me-1" style="width: 14px;"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results -->
        <div id="noUserResults" class="card-modern p-5 text-center d-none">
            <i data-lucide="search-x" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
            <h5 class="fw-bold">Tidak ada hasil ditemukan</h5>
            <p class="text-muted small mb-0">Coba gunakan kata kunci pencarian lainnya.</p>
        </div>
    @endif
</div>

<style>
    .avatar-circle {
        transition: var(--transition-base);
    }
    .user-row:hover .avatar-circle {
        transform: scale(1.1);
    }
    .fs-xs { font-size: 0.75rem; }
    .btn-capsule-danger {
        background-color: #fef2f2;
        color: var(--danger) !important;
        border: 1px solid #fee2e2;
    }
    .btn-capsule-danger:hover {
        background-color: var(--danger);
        color: white !important;
        transform: translateY(-2px);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const userRows = document.querySelectorAll('.user-row');
    const userCards = document.querySelectorAll('.user-card-mobile');
    const noResults = document.getElementById('noUserResults');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let hasResults = false;

            // Search in Desktop Table
            userRows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData.includes(query)) {
                    row.classList.remove('d-none');
                    hasResults = true;
                } else {
                    row.classList.add('d-none');
                }
            });

            // Search in Mobile Cards
            userCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(query)) {
                    card.classList.remove('d-none');
                    hasResults = true;
                } else {
                    card.classList.add('d-none');
                }
            });

            // Toggle No Results
            if (hasResults) {
                noResults.classList.add('d-none');
            } else {
                noResults.classList.remove('d-none');
            }
        });
    }
});
</script>
@endsection
