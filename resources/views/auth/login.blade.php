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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 1060px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .brand-hero {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .brand-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .quick-role-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .quick-role-card:hover {
            border-color: #0284c7;
            background: #f0f9ff;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="row g-0">
            <!-- Left Hero Section -->
            <div class="col-lg-5 brand-hero">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 mb-4 bg-white bg-opacity-20 px-3 py-1 rounded-pill">
                        <i class="fa-solid fa-building-columns"></i>
                        <span class="fw-bold fs-7">Sistem Informasi Kelurahan</span>
                    </div>

                    <h2 class="fw-extrabold text-white mb-3">SIPDK</h2>
                    <p class="text-white-50 leading-relaxed mb-4">
                        Sistem Informasi Persuratan & Disposisi Digital Kelurahan Dukuh, Kecamatan Sukoharjo. Digitalisasi arsip dan disposisi surat secara instan dan efisien.
                    </p>
                </div>

                <div class="border-top border-white border-opacity-20 pt-3">
                    <small class="text-white-50">
                        <i class="fa-solid fa-lock me-1"></i> Intranet Local Office Network
                    </small>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="col-lg-7 p-4 p-md-5">
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Masuk ke Sistem</h4>
                    <p class="text-muted fs-7">Silakan login menggunakan akun kedinasan Anda</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger rounded-3 fs-7 mb-4">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-7">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="nama@kelurahan.go.id" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark fs-7">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold mb-4 shadow-sm" style="background:#0284c7; border:none;">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sistem
                    </button>
                </form>

                <!-- Quick Login Role Buttons -->
                <div class="border-top pt-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark fs-7">
                            <i class="fa-solid fa-users text-primary me-1"></i> Akses Cepat Petugas Kelurahan:
                        </div>
                        <small class="text-muted" style="font-size:0.75rem;">(Klik untuk login simulasi)</small>
                    </div>

                    <div class="row g-2">
                        @foreach($demoUsers as $du)
                            <div class="col-md-6">
                                <form action="{{ route('quick-login') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $du->id }}">
                                    <button type="submit" class="quick-role-card w-100 text-start border d-flex flex-column justify-content-between h-100 p-2 rounded-3 shadow-xs">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size:0.85rem; line-height:1.25;">
                                                {{ $du->name }}
                                            </div>
                                            <div class="text-muted" style="font-size:0.75rem; margin-top:2px;">
                                                {{ $du->jabatan ?? ($du->department->name ?? 'Staf Kelurahan') }}
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex align-items-center gap-1">
                                            @if($du->role->name === 'pimpinan')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold" style="font-size:0.68rem;">Pimpinan</span>
                                            @elseif($du->role->name === 'admin')
                                                <span class="badge bg-dark-subtle text-dark border fw-semibold" style="font-size:0.68rem;">Administrator</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold" style="font-size:0.68rem;">Pelaksana</span>
                                            @endif
                                            @if($du->department)
                                                <span class="text-muted" style="font-size:0.68rem;">• {{ $du->department->code }}</span>
                                            @endif
                                        </div>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
