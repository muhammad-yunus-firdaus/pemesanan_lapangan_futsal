@extends('layouts.auth')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-card {
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
    .login-left {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px 40px;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
        gap: 10px;
    }

    .login-illustration img {
        width: 230px;
        height: 230px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        margin-bottom: 25px;
        animation: floatImage 3s ease-in-out infinite;
    }

    @keyframes floatImage {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .login-left h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .login-left p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        max-width: 300px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* BAGIAN KANAN */
    .login-right {
        flex: 1;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-header h3 {
        color: #2d3748;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .login-header p {
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
        padding: 12px 45px 12px 16px;
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
        right: 14px;
        top: 67%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #718096;
        cursor: pointer;
        font-size: 18px;
    }

    .btn-login {
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

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
    }

    .register-link {
        text-align: center;
        margin-top: 25px;
        color: #718096;
        font-size: 14px;
    }

    .register-link a {
        color: #0ea5e9;
        font-weight: 600;
        text-decoration: none;
    }

    /* Hide browser default password toggle */
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }

    input[type="password"]::-webkit-contacts-auto-fill-button,
    input[type="password"]::-webkit-credentials-auto-fill-button {
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        right: 0;
    }


    .input-group-text {
        z-index: 10;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
        }

        .login-left {
            padding: 40px 30px;
        }

        .login-right {
            padding: 40px 30px;
        }

        .login-illustration img {
            width: 180px;
            height: 180px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <!-- BAGIAN KIRI -->
        <div class="login-left">
            <div class="login-illustration">
                <img src="{{ asset('images/bola-login.png') }}" alt="Football Illustration">
            </div>
            <h2>Selamat Datang!</h2>

        </div>

        <!-- BAGIAN KANAN -->
        <div class="login-right">
            <div class="login-header">
                <h3>Masuk</h3>
                <p>Silakan gunakan username Anda untuk masuk.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="login" class="form-label">Username</label>
                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                        name="login" value="{{ old('login') }}" placeholder="Masukkan username Anda" required autofocus>
                    @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Masukkan password Anda" required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">Masuk</button>

                <div class="register-link">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.innerHTML = type === 'password'
            ? '<i class="fas fa-eye"></i>'
            : '<i class="fas fa-eye-slash"></i>';
    });
});
</script>
@endsection
