@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
    <!-- Welcome Banner -->
    <div class="card-custom p-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill text-white fs-7 mb-2" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <i class="fa-regular fa-calendar-check"></i>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <h3 class="fw-bold mb-1">Selamat Datang, {{ $user->name }}!</h3>
                <p class="text-white-50 m-0 fs-7">
                    Anda masuk sebagai <span class="badge bg-primary text-white">{{ $user->role->display_name }}</span> 
                    @if($user->department) - {{ $user->department->name }} @endif. Monitoring persuratan kelurahan hari ini:
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 d-flex justify-content-lg-end gap-2 flex-wrap">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('letters.create') }}" class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" style="background:#0284c7; border:none;">
                        <i class="fa-solid fa-plus-circle me-1"></i> Surat Masuk
                    </a>
                    <a href="{{ route('outgoing-letters.create') }}" class="btn btn-outline-light rounded-3 px-3 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Surat Keluar
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->isPelaksana())
    <!-- Stat Cards Grid (Khusus Pelaksana) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <span class="badge badge-status badge-didisposisi">Menunggu</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $myTaskCounts['menunggu'] }}</h2>
                    <small class="text-muted fw-semibold">Tugas Menunggu Tindak Lanjut</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <span class="badge badge-status badge-dipproses">Diproses</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $myTaskCounts['diproses'] }}</h2>
                    <small class="text-muted fw-semibold">Tugas Sedang Dikerjakan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="badge badge-status badge-selesai">Selesai</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $myTaskCounts['selesai'] }}</h2>
                    <small class="text-muted fw-semibold">Tugas Berhasil Diselesaikan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <span class="badge bg-secondary text-white">Total</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $myTaskCounts['total'] }}</h2>
                    <small class="text-muted fw-semibold">Total Disposisi Masuk</small>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Stat Cards Grid (Admin & Pimpinan) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-envelope-open"></i>
                    </div>
                    <span class="badge badge-status badge-baru">Hari Ini</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $suratHariIni }}</h2>
                    <small class="text-muted fw-semibold">Surat Masuk Hari Ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <span class="badge badge-status badge-didisposisi">Belum Diproses</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $suratBaru }}</h2>
                    <small class="text-muted fw-semibold">Menunggu Disposisi</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-arrows-spin"></i>
                    </div>
                    <span class="badge badge-status badge-dipproses">Dalam Disposisi</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $suratDiproses }}</h2>
                    <small class="text-muted fw-semibold">Sedang Ditindaklanjuti</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:44px; height:44px; font-size:1.15rem;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="badge badge-status badge-selesai">Selesai</span>
                </div>
                <div>
                    <h2 class="fw-extrabold text-dark mb-1" style="font-size:2.2rem; letter-spacing:-1px;">{{ $suratSelesai }}</h2>
                    <small class="text-muted fw-semibold">Tugas Selesai & Terarsip</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Pending Dispositions & Recent Letters -->
        <div class="col-lg-8">

            <!-- Empty state for Pelaksana when no pending tasks -->
            @if(auth()->user()->isPelaksana() && $myPendingDispositions->count() == 0)
                <div class="card-custom p-5 text-center mb-4">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px; height:64px; font-size:1.75rem;">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Semua Tugas Sudah Selesai!</h5>
                    <p class="text-muted mb-4 fs-6">Tidak ada disposisi surat yang menunggu tindak lanjut Anda saat ini.</p>
                    <a href="{{ route('dispositions.index') }}" class="btn btn-outline-primary rounded-3 px-4 fw-bold">
                        <i class="fa-solid fa-clipboard-list me-1"></i> Buka Halaman Tugas Saya
                    </a>
                </div>
            @endif

            <!-- Pending Dispositions Action Board for User -->
            @if($myPendingDispositions->count() > 0)
                <div class="card-custom mb-4 border-start border-4 border-warning">
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-warning-subtle rounded-top-4">
                        <h6 class="fw-bold m-0 text-warning-emphasis fs-5">
                            <i class="fa-solid fa-bell me-2"></i> Tugas Anda ({{ $myPendingDispositions->count() }} Menunggu)
                        </h6>
                    </div>
                    <div class="p-3">
                        <div class="list-group list-group-flush">
                            @foreach($myPendingDispositions as $disp)
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div>
                                            <span class="badge bg-secondary text-white mb-1" style="font-size:0.8rem;">{{ $disp->letter->agenda_number }}</span>
                                            <h5 class="fw-bold text-dark m-0 mb-2">{{ $disp->letter->subject }}</h5>
                                            <div class="p-3 bg-light rounded-3 mb-3 border border-warning-subtle">
                                                <p class="text-dark fs-6 mb-1">
                                                    <strong>Instruksi {{ $disp->sender->name }}:</strong><br>
                                                    "{{ $disp->instruction }}"
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 fs-6 text-muted">
                                                <span><i class="fa-solid fa-user-pen me-1"></i> Pengirim: {{ $disp->sender->name }}</span>
                                                @if($disp->due_date)
                                                    <span class="text-danger fw-bold"><i class="fa-regular fa-clock me-1"></i> Batas Waktu: {{ $disp->due_date->format('d/m/Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-lg btn-success rounded-3 px-4 py-2 fw-bold shadow-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalFollowUp{{ $disp->id }}">
                                                <i class="fa-solid fa-check-circle me-2"></i> Laporkan Selesai
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Follow Up Modal -->
                                <div class="modal fade" id="modalFollowUp{{ $disp->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold">Laporan Selesai Pekerjaan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('dispositions.follow-up', $disp->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Status Tindak Lanjut</label>
                                                        <select name="status" class="form-select form-select-lg" required>
                                                            <option value="Diproses">Diproses (Masih Dikerjakan)</option>
                                                            <option value="Selesai" selected>Selesai (Sudah Selesai)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Catatan Hasil Pekerjaan</label>
                                                        <textarea name="follow_up_notes" class="form-control" rows="4" placeholder="Tuliskan hasil kerja yang sudah diselesaikan..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top p-3">
                                                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success fw-bold px-4 py-2"><i class="fa-solid fa-save me-2"></i> Simpan Laporan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(!auth()->user()->isPelaksana())
            <!-- Recent Letters Table Card -->
            <div class="card-custom p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-inbox me-2 text-primary"></i> Surat Masuk Terbaru
                    </h5>
                    <a href="{{ route('letters.index') }}" class="btn btn-light fw-bold px-3 py-2 rounded-3">Lihat Semua Surat Masuk</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:1rem;">
                        <thead class="table-light">
                            <tr>
                                <th>No. Agenda / Surat</th>
                                <th>Pengirim</th>
                                <th>Perihal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLetters as $letter)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary d-block">{{ $letter->agenda_number }}</span>
                                        <small class="text-muted">{{ $letter->reference_number }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ Str::limit($letter->sender, 22) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark d-block fw-bold">{{ Str::limit($letter->subject, 40) }}</span>
                                        <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> {{ $letter->received_date->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($letter->status) {
                                                'Baru' => 'badge-baru',
                                                'Dibaca' => 'badge-dibaca',
                                                'Didisposisi' => 'badge-didisposisi',
                                                'Diproses' => 'badge-dipproses',
                                                'Selesai' => 'badge-selesai',
                                                default => 'badge-arsip'
                                            };
                                        @endphp
                                        <span class="badge badge-status {{ $badgeClass }} fs-7">{{ $letter->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Buka Surat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-envelope-open-text fs-1 mb-3 text-light"></i><br>
                                        Belum ada surat masuk terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Sent Dispositions Monitoring & Audit Feed -->
        <div class="col-lg-4">

            @if(auth()->user()->isPimpinan() || auth()->user()->isAdmin())
            <!-- Sent Dispositions Monitoring Card -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-eye me-2 text-info"></i> Pantauan Disposisi Anda
                </h5>

                <div class="d-flex flex-column gap-3">
                    @forelse($sentDispositions as $sd)
                        <div class="p-3 rounded-3 bg-light border border-info-subtle shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-secondary text-white px-2 py-1 fs-7">{{ $sd->letter->agenda_number }}</span>
                                <span class="badge {{ $sd->status == 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-1 fs-7">{{ $sd->status }}</span>
                            </div>
                            <div class="fw-bold text-dark fs-6 mb-1">Diberikan ke: {{ $sd->recipient->name }}</div>
                            <div class="text-dark bg-white p-2 border rounded-2 fst-italic">"{{ $sd->instruction }}"</div>
                        </div>
                    @empty
                        <p class="text-muted fs-6 m-0 py-3 text-center">Belum ada disposisi yang Anda kirimkan.</p>
                    @endforelse
                </div>
            </div>
            @endif

            @if(auth()->user()->isAdmin())
            <!-- Audit Trail Feed -->
            <div class="card-custom p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-history me-2 text-secondary"></i> Catatan Aktivitas Sistem
                    </h5>
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary fw-bold px-3 py-1 rounded-3">Lihat Semua</a>
                </div>

                <div class="d-flex flex-column gap-3">
                    @foreach($recentAudits as $log)
                        <div class="d-flex gap-3 align-items-start border-bottom pb-3">
                            <div class="bg-light text-primary rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; font-size:1rem;">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark fs-6">{{ $log->user_name }}</div>
                                <span class="text-dark d-block fs-6">{{ $log->action }} ({{ $log->module }})</span>
                                <span class="text-muted" style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(auth()->user()->isPelaksana())
            <!-- Pelaksana Guide & Info Card -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-circle-info me-2 text-primary"></i> Alur Kerja Disposisi
                </h5>
                <div class="d-flex flex-column gap-3 text-muted" style="font-size:0.88rem;">
                    <div class="d-flex gap-2 align-items-start">
                        <span class="badge bg-primary rounded-circle mt-1" style="width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; font-size:0.7rem;">1</span>
                        <span>Surat masuk ditelaah & didisposisikan oleh <strong>Pimpinan (Lurah)</strong>.</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <span class="badge bg-primary rounded-circle mt-1" style="width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; font-size:0.7rem;">2</span>
                        <span>Pelaksana menerima notifikasi & instruksi resmi pada daftar <strong>Tugas Saya</strong>.</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <span class="badge bg-primary rounded-circle mt-1" style="width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; font-size:0.7rem;">3</span>
                        <span>Lakukan tindak lanjut dan klik <strong>Laporkan Selesai</strong> untuk mengarsipkan tugas.</span>
                    </div>
                </div>
                <hr class="my-3">
                <a href="{{ route('dispositions.index') }}" class="btn btn-light w-100 fw-bold text-primary border">
                    <i class="fa-solid fa-arrow-right me-1"></i> Buka Menu Tugas Saya
                </a>
            </div>
            @endif

        </div>
    </div>
@endsection
