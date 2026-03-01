<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | Lembang Arena</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            text-align: center;
            max-width: 500px;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #ef4444;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 4px 4px 0 #fecaca;
        }
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }
        .error-message {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ef4444;
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .btn-home:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }
        @media (max-width: 480px) {
            .error-code {
                font-size: 5rem;
            }
            .error-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-icon">🔧</div>
        <h1 class="error-title">Kesalahan Server</h1>
        <p class="error-message">
            Maaf, terjadi kesalahan pada server kami. 
            Tim teknis sedang bekerja untuk memperbaikinya. 
            Silakan coba lagi dalam beberapa saat.
        </p>
        <a href="{{ url('/') }}" class="btn-home">
            ← Kembali ke Beranda
        </a>
    </div>
</body>
</html>
