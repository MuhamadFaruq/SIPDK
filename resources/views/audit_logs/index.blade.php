@extends('layouts.app')

@section('title', 'Audit Log Aktivitas')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Audit Log & Catatan Aktivitas Sistem</h4>
            <small class="text-muted">Pencatatan 100% transparan seluruh aksi pengguna dalam sistem SIPDK</small>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Cari Pengguna / Aksi / Deskripsi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-7" placeholder="Nama user, action, deskripsi..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Filter Modul</label>
                <select name="module" class="form-select bg-light fs-7">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold fs-7 rounded-3 py-2">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary fw-bold fs-7 rounded-3 py-2">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.86rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 160px;">Waktu & IP</th>
                        <th>Pengguna & Peran</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Rincian Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</div>
                                <small class="text-muted font-monospace"><i class="fa-solid fa-network-wired me-1"></i> {{ $log->ip_address ?? 'Local' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $log->user_name }}</div>
                                <span class="badge bg-secondary text-white" style="font-size:0.65rem;">{{ $log->role_name ?? 'User' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">{{ $log->action }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $log->module }}</span>
                            </td>
                            <td>
                                <span class="text-dark">{{ $log->description ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada catatan audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
