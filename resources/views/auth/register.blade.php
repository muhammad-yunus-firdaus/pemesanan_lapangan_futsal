@extends('layouts.auth')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .register-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        max-width: 950px;
        width: 100%;
        display: flex;
        flex-direction: row;
        animation: fadeInUp 0.6s ease;
    }

    /* BAGIAN KIRI */
    .register-left {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px 40px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
        gap: 10px;
    }

    .register-left::before,
    .register-left::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }

    .register-left::before {
        width: 300px;
        height: 300px;
        top: -100px;
        left: -100px;
    }

    .register-left::after {
        width: 200px;
        height: 200px;
        bottom: -50px;
        right: -50px;
    }

    .register-illustration img {
        width: 220px;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        margin-bottom: 25px;
        animation: floatImage 3s ease-in-out infinite;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .register-illustration img:hover {
        transform: scale(1.05);
    }

    @keyframes floatImage {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .register-left h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .register-left p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        line-height: 1.6;
        max-width: 320px;
        margin: 0 auto;
    }

    /* BAGIAN KANAN */
    .register-right {
        flex: 1;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .register-header h3 {
        color: #2d3748;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .register-header p {
        color: #718096;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 22px;
        position: relative;
    }

    .form-label {
        color: #4a5568;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: #f7fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0ea5e9;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 65%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #718096;
        cursor: pointer;
        font-size: 18px;
        transition: color 0.3s;
    }

    .toggle-password:hover {
        color: #0ea5e9;
    }

    .btn-register {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.35);
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
    }

    .login-link {
        text-align: center;
        margin-top: 25px;
        color: #718096;
        font-size: 14px;
    }

    .login-link a {
        color: #0ea5e9;
        font-weight: 600;
        text-decoration: none;
    }

    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    input[type="password"]::-webkit-reveal-button {
        display: none !important;
    }

    .toggle-password {
        z-index: 10;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .register-card {
            flex-direction: column;
        }

        .register-left {
            padding: 40px 30px;
        }

        .register-right {
            padding: 40px 30px;
        }

        .register-illustration img {
            width: 150px;
            margin-bottom: 15px;
        }
    }
</style>

<div class="register-container">
    <div class="register-card">
        <!-- BAGIAN KIRI -->
        <div class="register-left">
            <div class="register-illustration">
                <img src="{{ asset('images/bola-login.png') }}" alt="Football Illustration">
            </div>
            <h2>Bergabunglah Bersama Kami!</h2>
            <p>Daftar sekarang dan nikmati kemudahan booking lapangan futsal.</p>
        </div>

        <!-- BAGIAN KANAN -->
        <div class="register-right">
            <div class="register-header">
                <h3>Daftar</h3>
                <p>Buat akun baru untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required autofocus>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" placeholder="Masukkan username Anda" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                    <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror"
                        name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789" required>
                    @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Minimal 8 karakter" required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <input id="password-confirm" type="password" class="form-control"
                        name="password_confirmation" placeholder="Ketik ulang password Anda" required>
                    <button type="button" class="toggle-password" id="togglePasswordConfirm">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn-register">Daftar Sekarang</button>

                <div class="login-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK TOGGLE PASSWORD -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
    const passwordConfirm = document.querySelector('#password-confirm');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    togglePasswordConfirm.addEventListener('click', function () {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
@endsection
