<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPDK Kelurahan Dukuh</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0b1329 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 880px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-hero {
            background: linear-gradient(145deg, #0284c7 0%, #0369a1 60%, #075985 100%);
            color: white;
            padding: 3.5rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .brand-hero::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .brand-pill {
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffffff;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.85rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.88);
        }

        .feature-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .form-control:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3.5px rgba(2, 132, 199, 0.15);
        }

        .input-group-text {
            border-color: #dee2e6;
        }

        .btn-login {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="row g-0">
            <!-- Left Hero Section -->
            <div class="col-lg-5 brand-hero">
                <div class="position-relative" style="z-index: 2;">
                    <div class="brand-pill d-inline-flex align-items-center gap-2 mb-4 px-3 py-1.5 rounded-pill">
                        <i class="fa-solid fa-building-columns text-white"></i>
                        <span class="fw-bold text-white">Kelurahan Dukuh</span>
                    </div>

                    <h2 class="fw-extrabold text-white mb-2" style="font-size: 2.1rem; letter-spacing: -0.5px;">SIPDK</h2>
                    <p class="text-white text-opacity-75 fs-7 mb-4" style="line-height: 1.6;">
                        Sistem Informasi Persuratan & Disposisi Digital Kelurahan Dukuh, Kecamatan Sukoharjo.
                    </p>

                    <div class="pt-2">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                            <span>Registrasi Surat Masuk & Keluar</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fa-solid fa-share-nodes"></i></div>
                            <span>Alur Disposisi & Tindak Lanjut</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <span>Arsip Digital Aman & Terintegrasi</span>
                        </div>
                    </div>
                </div>

                <div class="border-top border-white border-opacity-20 pt-3 mt-4 position-relative" style="z-index: 2;">
                    <small class="text-white-50 d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-circle-check text-white text-opacity-75"></i> Sistem Resmi Intranet Kelurahan Dukuh
                    </small>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">Masuk ke Sistem</h3>
                    <p class="text-muted" style="font-size: 0.875rem;">Silakan login menggunakan akun kedinasan Anda</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger rounded-3 fs-7 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success rounded-3 fs-7 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 py-2.5 @error('email') is-invalid @enderror" placeholder="nama@kelurahan.go.id" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" id="passwordInput" name="password" class="form-control bg-light border-start-0 border-end-0 py-2.5" placeholder="••••••••" required>
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted px-3" onclick="togglePassword()" style="cursor: pointer;" title="Lihat/Sembunyikan Kata Sandi">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label text-muted" for="rememberMe" style="font-size: 0.82rem;">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sistem
                    </button>
                </form>

                <div class="mt-4 pt-3 text-center border-top">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info me-1 text-primary"></i> Hubungi Administrator Kelurahan jika Anda lupa kata sandi akun.
                    </small>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
