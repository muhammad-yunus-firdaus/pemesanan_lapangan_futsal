@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="container-fluid px-2 px-md-3">
    <div class="row g-4">
        <!-- FORM COLUMN -->
        <div class="col-12 col-xl-8">
            <div class="card-modern border-0 shadow-sm overflow-hidden mb-5">
                <div class="p-4 border-bottom bg-light">
                    <h5 class="fw-bold mb-0 text-heading d-flex align-items-center gap-2">
                        <i data-lucide="user-plus" class="text-primary"></i>
                        Pendaftaran Akun Baru
                    </h5>
                </div>
                
                <div class="p-4 p-md-5">
                    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Section: Personal Info -->
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">1</div>
                                    <h6 class="fw-bold mb-0 text-heading">Informasi Pribadi</h6>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Nama Lengkap</label>
                                <div class="position-relative">
                                    <i data-lucide="user" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="name" id="userNameInput" 
                                           class="input-modern ps-5 @error('name') is-invalid @enderror" 
                                           placeholder="Contoh: Muhammad Jhon" 
                                           value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Nomor Telepon</label>
                                <div class="position-relative">
                                    <i data-lucide="phone" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="phone_number" id="userPhoneInput" 
                                           class="input-modern ps-5 @error('phone_number') is-invalid @enderror" 
                                           placeholder="0812XXXXXXXX" 
                                           value="{{ old('phone_number') }}" required>
                                </div>
                                @error('phone_number')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Email (Opsional)</label>
                                <div class="position-relative">
                                    <i data-lucide="mail" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="email" name="email" id="userEmailInput" 
                                           class="input-modern ps-5 @error('email') is-invalid @enderror" 
                                           placeholder="jhon@example.com" 
                                           value="{{ old('email') }}">
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Section: Account Security -->
                            <div class="col-12 mt-5">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">2</div>
                                    <h6 class="fw-bold mb-0 text-heading">Keamanan Akun</h6>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Username</label>
                                <div class="position-relative">
                                    <i data-lucide="at-sign" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="text" name="username" id="userUsernameInput" 
                                           class="input-modern ps-5 @error('username') is-invalid @enderror" 
                                           placeholder="jhon_futsal" 
                                           value="{{ old('username') }}" required>
                                </div>
                                @error('username')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Password</label>
                                <div class="position-relative">
                                    <i data-lucide="lock-keyhole" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="password" name="password" 
                                           class="input-modern ps-5 @error('password') is-invalid @enderror" 
                                           placeholder="••••••••" required>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-heading small">Konfirmasi Password</label>
                                <div class="position-relative">
                                    <i data-lucide="shield-check" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width: 18px;"></i>
                                    <input type="password" name="password_confirmation" 
                                           class="input-modern ps-5" 
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Section: Role Access -->
                            <div class="col-12 mt-5">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">3</div>
                                    <h6 class="fw-bold mb-0 text-heading">Hak Akses</h6>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-heading small">Pilih Role</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="role" id="roleUser" value="user" {{ old('role', 'user') == 'user' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary w-100 p-3 rounded-4 d-flex align-items-center gap-3" for="roleUser">
                                            <div class="icon-box-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i data-lucide="users" style="width: 20px;"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold">Customer</div>
                                                <div class="small opacity-75">Akses Pemesanan</div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="role" id="roleAdmin" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger w-100 p-3 rounded-4 d-flex align-items-center gap-3" for="roleAdmin">
                                            <div class="icon-box-sm bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i data-lucide="shield-check" class="text-danger" style="width: 20px;"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold">Administrator</div>
                                                <div class="small opacity-75">Akses Panel Admin</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('role')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-4 pt-4 border-top">
                            <div class="col-12 col-md-auto order-1 order-md-2">
                                <button type="submit" class="btn-capsule btn-capsule-primary w-100 px-md-5 py-2 py-md-3">
                                    <i data-lucide="user-plus" class="me-2"></i> Daftarkan Pengguna
                                </button>
                            </div>
                            <div class="col-12 col-md-auto order-2 order-md-1 ms-md-auto">
                                <a href="{{ route('admin.users.index') }}" class="btn-capsule btn-capsule-light border w-100 px-md-5 py-2 py-md-3 text-center">
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
            <div class="sticky-top" style="top: 2rem; z-index: 10;">
                <h6 class="fw-bold text-heading mb-3 d-flex align-items-center gap-2">
                    <i data-lucide="eye" class="text-primary" style="width: 18px;"></i>
                    Profile Preview
                </h6>
                
                <div class="card-modern overflow-hidden border-0 shadow-lg animate-fade-in text-center p-5">
                    <!-- Avatar Preview -->
                    <div class="position-relative d-inline-block mb-4">
                        <div id="avatarContainer" class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto transition-base" style="width: 120px; height: 120px; border: 4px solid white; box-shadow: var(--shadow-md);">
                            <i data-lucide="user" id="previewIcon" class="text-primary" style="width: 50px; height: 50px;"></i>
                        </div>
                        <div id="roleBadge" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; border: 2px solid white;">
                            <i data-lucide="users" style="width: 16px;"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold text-heading mb-1" id="previewName">Nama Pengguna</h4>
                    <p class="text-muted small mb-3" id="previewUsername">@username</p>
                    
                    <div class="badge-pill-modern mb-4 mx-auto" id="previewRoleLabel" style="width: fit-content; background-color: var(--primary-subtle); color: var(--primary); padding: 5px 15px; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">
                        CUSTOMER
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="row g-2 text-start small">
                        <div class="col-12 d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="phone" style="width: 14px;"></i>
                            <span id="previewPhone">-</span>
                        </div>
                        <div class="col-12 d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="mail" style="width: 14px;"></i>
                            <span id="previewEmail">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert-modern mt-4">
                    <div class="d-flex gap-2">
                        <i data-lucide="info" class="text-primary mt-1" style="width: 18px; min-width: 18px;"></i>
                        <p class="mb-0 small">
                            <strong>Penting:</strong> Pastikan nomor telepon aktif untuk memudahkan koordinasi pemesanan lapangan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    
    .btn-check:checked + .btn-outline-primary {
        background-color: #eef2ff;
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn-check:checked + .btn-outline-danger {
        background-color: #fef2f2;
        border-color: #dc2626;
        color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .transition-base { transition: all 0.3s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('userNameInput');
    const usernameInput = document.getElementById('userUsernameInput');
    const phoneInput = document.getElementById('userPhoneInput');
    const emailInput = document.getElementById('userEmailInput');
    const roleRadios = document.querySelectorAll('input[name="role"]');
    
    const previewName = document.getElementById('previewName');
    const previewUsername = document.getElementById('previewUsername');
    const previewPhone = document.getElementById('previewPhone');
    const previewEmail = document.getElementById('previewEmail');
    const previewRoleLabel = document.getElementById('previewRoleLabel');
    const avatarContainer = document.getElementById('avatarContainer');
    const previewIcon = document.getElementById('previewIcon');
    const roleBadge = document.getElementById('roleBadge');

    function updatePreview() {
        previewName.textContent = nameInput.value || 'Nama Pengguna';
        previewUsername.textContent = '@' + (usernameInput.value || 'username');
        previewPhone.textContent = phoneInput.value || '-';
        previewEmail.textContent = emailInput.value || '-';
        
        // Update Role Visuals
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        if (selectedRole === 'admin') {
            previewRoleLabel.textContent = 'ADMINISTRATOR';
            previewRoleLabel.style.backgroundColor = '#fef2f2';
            previewRoleLabel.style.color = '#dc2626';
            
            avatarContainer.style.backgroundColor = '#fef2f2';
            previewIcon.style.color = '#dc2626';
            previewIcon.innerHTML = `<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>`; // Shield icon
            
            roleBadge.style.backgroundColor = '#dc2626';
            roleBadge.innerHTML = `<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>`;
            lucide.replace();
        } else {
            previewRoleLabel.textContent = 'CUSTOMER';
            previewRoleLabel.style.backgroundColor = '#eef2ff';
            previewRoleLabel.style.color = '#3b82f6';
            
            avatarContainer.style.backgroundColor = '#eef2ff';
            previewIcon.style.color = '#3b82f6';
            previewIcon.innerHTML = `<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>`; // User icon
            
            roleBadge.style.backgroundColor = '#3b82f6';
            roleBadge.innerHTML = `<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>`; // Users icon
            lucide.replace();
        }
    }

    nameInput.addEventListener('input', updatePreview);
    usernameInput.addEventListener('input', updatePreview);
    phoneInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);
    roleRadios.forEach(radio => radio.addEventListener('change', updatePreview));

    // Initialize
    updatePreview();
});
</script>
@endsection
