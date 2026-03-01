@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-primary text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-primary text-decoration-none">Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <h4 class="fw-bold text-heading mb-0">Edit Profil Pengguna</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card-modern shadow-sm pb-2">
                <div class="p-4 border-bottom bg-light" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light text-primary border rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i data-lucide="user" style="width: 22px;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-heading mb-0">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">Perbarui informasi akun atau ubah hak akses pengguna.</p>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold text-heading small">Nama Lengkap</label>
                                <div class="position-relative">
                                    <i data-lucide="user" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="name" id="name" class="input-modern ps-5 @error('name') is-invalid @enderror" 
                                        value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap..." required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block mt-2 ms-1 small fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-12">
                                <label for="phone_number" class="form-label fw-semibold text-heading small">Nomor Telepon</label>
                                <div class="position-relative">
                                    <i data-lucide="phone" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="phone_number" id="phone_number" class="input-modern ps-5 @error('phone_number') is-invalid @enderror" 
                                        value="{{ old('phone_number', $user->phone_number) }}" placeholder="08123456789" required>
                                </div>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block mt-2 ms-1 small fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold text-heading small">Alamat Email (Opsional)</label>
                                <div class="position-relative">
                                    <i data-lucide="mail" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="email" name="email" id="email" class="input-modern ps-5 @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" placeholder="contoh@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2 ms-1 small fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3 border-start border-primary border-4">
                                    <div class="d-flex gap-2">
                                        <i data-lucide="info" class="text-primary mt-1" style="width: 16px;"></i>
                                        <p class="text-muted small mb-0">Kosongkan kolom password di bawah ini jika tidak ingin mengubah password lama.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label fw-semibold text-heading small">Password Baru (Opsional)</label>
                                <div class="position-relative">
                                    <i data-lucide="lock" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="password" name="password" id="password" class="input-modern ps-5 @error('password') is-invalid @enderror" placeholder="••••••••">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-2 ms-1 small fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-heading small">Konfirmasi Password Baru</label>
                                <div class="position-relative">
                                    <i data-lucide="shield-check" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-modern ps-5" placeholder="••••••••">
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="col-12">
                                <label for="role" class="form-label fw-semibold text-heading small">Role Akses</label>
                                <div class="position-relative">
                                    <i data-lucide="shield" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px; z-index: 10;"></i>
                                    <select name="role" id="role" class="input-modern ps-5 @error('role') is-invalid @enderror" required>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Customer (Akses Pemesanan)</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block mt-2 ms-1 small fw-medium">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row g-3 mt-4 pt-4 border-top">
                            <div class="col-12 col-md-auto order-1 order-md-2">
                                <button type="submit" class="btn-capsule btn-capsule-primary w-100 px-md-5 py-2 py-md-3 fw-semibold">
                                    <i data-lucide="user-check" class="me-2" style="width: 16px;"></i> Perbarui Pengguna
                                </button>
                            </div>
                            <div class="col-12 col-md-auto order-2 order-md-1 ms-md-auto">
                                <a href="{{ route('admin.users.index') }}" class="btn-capsule btn-capsule-light border w-100 px-md-5 py-2 py-md-3 text-center fw-semibold">
                                    <i data-lucide="x-circle" class="me-2" style="width: 16px;"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
