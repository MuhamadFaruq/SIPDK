@extends('layouts.app')

@section('title', 'Detail Surat & Disposisi - ' . $letter->agenda_number)

@section('content')
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <a href="{{ route('letters.index') }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold text-dark m-0">Detail Surat Agenda: <span class="text-primary font-monospace">{{ $letter->agenda_number }}</span></h4>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('letters.print-agenda', $letter->id) }}" target="_blank" class="btn btn-outline-secondary fw-bold rounded-3 fs-7">
                <i class="fa-solid fa-print me-1"></i> Cetak Agenda
            </a>
            <a href="{{ route('dispositions.print-sheet', $letter->id) }}" target="_blank" class="btn btn-outline-success fw-bold rounded-3 fs-7">
                <i class="fa-solid fa-file-invoice me-1"></i> Cetak Lembar Disposisi
            </a>
            
            @if(auth()->user()->isPimpinan() || auth()->user()->isAdmin())
                <button type="button" class="btn btn-primary fw-bold rounded-3 fs-7 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKirimDisposisi" style="background:#0284c7; border:none;">
                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim Disposisi Baru
                </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: Letter Info & Embedded PDF Viewer -->
        <div class="col-lg-7">
            <!-- Metadata Card -->
            <div class="card-custom p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div>
                        <span class="badge bg-dark text-white font-monospace mb-1 fs-7">{{ $letter->agenda_number }}</span>
                        <h5 class="fw-bold text-dark m-0">{{ $letter->subject }}</h5>
                    </div>
                    <div>
                        @php
                            $statusClass = match($letter->status) {
                                'Baru' => 'badge-baru',
                                'Didisposisi' => 'badge-didisposisi',
                                'Diproses' => 'badge-dipproses',
                                'Selesai' => 'badge-selesai',
                                default => 'badge-arsip'
                            };
                        @endphp
                        <span class="badge badge-status {{ $statusClass }} fs-7">{{ $letter->status }}</span>
                    </div>
                </div>

                <div class="row g-3 fs-7 mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Nomor Surat Fizik:</small>
                        <span class="fw-bold text-dark">{{ $letter->reference_number }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Pengirim:</small>
                        <span class="fw-bold text-dark"><i class="fa-solid fa-building text-secondary me-1"></i> {{ $letter->sender }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Tanggal Surat / Terima:</small>
                        <span class="fw-semibold text-dark">{{ $letter->letter_date->format('d/m/Y') }} (Diterima: {{ $letter->received_date->format('d/m/Y') }})</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Kategori & Sifat:</small>
                        <span class="badge bg-light text-dark border me-1">{{ $letter->category->name ?? '-' }}</span>
                        <span class="badge badge-status badge-penting">{{ $letter->degree }}</span>
                    </div>
                </div>

                @if($letter->summary)
                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <small class="fw-bold text-secondary d-block mb-1">Ringkasan / Catatan Isi Surat:</small>
                        <p class="m-0 fs-7 text-dark leading-relaxed">{{ $letter->summary }}</p>
                    </div>
                @endif
            </div>

            <!-- Embedded PDF Document Viewer Card -->
            <div class="card-custom p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-file-pdf me-2 text-danger"></i> Pratinjau Dokumen Surat
                    </h6>
                    @if($letter->file_path)
                        <a href="{{ route('letters.file', $letter->id) }}" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Fullscreen / Download
                        </a>
                    @endif
                </div>

                @if($letter->file_path && Storage::disk('public')->exists($letter->file_path))
                    <div class="rounded-3 border overflow-hidden" style="height: 550px; background: #525659;">
                        @if($letter->file_type === 'pdf')
                            <iframe src="{{ route('letters.file', $letter->id) }}" width="100%" height="100%" style="border:none;"></iframe>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 p-3">
                                <img src="{{ route('letters.file', $letter->id) }}" alt="Preview Dokumen" class="img-fluid rounded shadow" style="max-height:100%;">
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-light p-5 text-center rounded-3 border text-muted">
                        <i class="fa-solid fa-file-excel fs-1 d-block mb-2 text-secondary"></i>
                        Dokumen PDF tidak ditemukan atau belum diunggah.
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Disposition Visual Timeline & Logs -->
        <div class="col-lg-5">
            <div class="card-custom p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-diagram-project me-2 text-primary"></i> Riwayat & Disposisi
                    </h6>
                    <span class="badge bg-secondary text-white">{{ $letter->dispositions->count() }} Disposisi</span>
                </div>

                <!-- Dispositions List -->
                @forelse($letter->dispositions as $disp)
                    <div class="p-3 mb-3 rounded-3 border border-start border-4 {{ $disp->status == 'Selesai' ? 'border-success bg-success-subtle' : ($disp->status == 'Diproses' ? 'border-warning bg-warning-subtle' : 'border-primary bg-light') }}">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="fw-bold text-dark fs-7 d-block">
                                    <i class="fa-solid fa-user-gear me-1 text-primary"></i> Ke: {{ $disp->recipient->name }}
                                </span>
                                <small class="text-muted" style="font-size:0.7rem;">Dari: {{ $disp->sender->name }} ({{ $disp->sender->jabatan ?? $disp->sender->role->display_name }})</small>
                                @if($disp->parent_id)
                                    <span class="badge bg-info-subtle text-info d-inline-block mt-1" style="font-size:0.65rem;">
                                        <i class="fa-solid fa-code-fork me-1"></i> Disposisi Terusan
                                    </span>
                                @endif
                            </div>
                            <span class="badge {{ $disp->status == 'Selesai' ? 'bg-success' : ($disp->status == 'Diproses' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                {{ $disp->status }}
                            </span>
                        </div>

                        <div class="fs-7 text-dark mb-2">
                            <strong>Instruksi Disposisi:</strong>
                            <p class="m-0 text-muted fst-italic">"{{ $disp->instruction }}"</p>
                        </div>

                        @if($disp->due_date)
                            <small class="text-danger fw-semibold d-block mb-2">
                                <i class="fa-regular fa-calendar-xmark me-1"></i> Batas Waktu: {{ $disp->due_date->format('d/m/Y') }}
                            </small>
                        @endif

                        @if($disp->follow_up_notes)
                            <div class="p-2 bg-white rounded border fs-7 mt-2">
                                <strong class="text-success d-block mb-1"><i class="fa-solid fa-check-double me-1"></i> Laporan Tindak Lanjut:</strong>
                                <span class="text-dark">{{ $disp->follow_up_notes }}</span>
                                <small class="text-muted d-block mt-1" style="font-size:0.68rem;">Diberikan pada {{ $disp->followed_up_at ? $disp->followed_up_at->format('d/m/Y H:i') : '-' }}</small>
                            </div>
                        @endif

                        <!-- Action for Recipient User -->
                        @if(auth()->id() === $disp->recipient_user_id && $disp->status !== 'Selesai')
                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-info flex-fill fw-bold fs-7" data-bs-toggle="modal" data-bs-target="#modalForwardShow{{ $disp->id }}">
                                    <i class="fa-solid fa-share-nodes me-1"></i> Teruskan
                                </button>
                                <button type="button" class="btn btn-sm btn-success flex-fill fw-bold fs-7" data-bs-toggle="modal" data-bs-target="#modalFollowUpShow{{ $disp->id }}">
                                    <i class="fa-solid fa-check-circle me-1"></i> Lapor Selesai
                                </button>
                            </div>

                            <!-- Modal Teruskan Disposisi -->
                            <div class="modal fade" id="modalForwardShow{{ $disp->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        <div class="modal-header border-bottom p-3">
                                            <h5 class="modal-title fw-bold text-dark">
                                                <i class="fa-solid fa-share-nodes text-primary me-2"></i> Teruskan Disposisi ke Staf / Pelaksana
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('dispositions.forward', $disp->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Pilih Penerima Terusan <span class="text-danger">*</span></label>
                                                    <div class="row g-2 max-h-60 overflow-auto">
                                                        @foreach($recipients as $rec)
                                                            @if($rec->id !== auth()->id())
                                                                <div class="col-md-6">
                                                                    <label class="recipient-card d-flex align-items-start gap-2 p-3 rounded-3 border bg-light w-100 mb-0 user-select-none h-100" for="fwd_show_{{ $disp->id }}_{{ $rec->id }}" style="cursor: pointer;">
                                                                        <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" name="recipients[]" value="{{ $rec->id }}" id="fwd_show_{{ $disp->id }}_{{ $rec->id }}" style="cursor: pointer;">
                                                                        <div class="flex-grow-1">
                                                                            <div class="fw-bold text-dark fs-7 lh-sm">{{ $rec->name }}</div>
                                                                            <span class="badge bg-secondary text-white d-inline-block mt-1" style="font-size:0.65rem;">{{ $rec->jabatan ?? $rec->role->display_name }}</span>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Petunjuk / Arahan Tambahan <span class="text-danger">*</span></label>
                                                    <textarea name="instruction" class="form-control" rows="3" placeholder="Tuliskan arahan spesifik untuk staf pelaksana..." required></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-top p-3">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold px-4" style="background:#0284c7; border:none;">
                                                    <i class="fa-solid fa-paper-plane me-1"></i> Teruskan Disposisi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Follow Up -->
                            <div class="modal fade" id="modalFollowUpShow{{ $disp->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold">Tindak Lanjut Disposisi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('dispositions.follow-up', $disp->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status Tindak Lanjut</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Diproses">Diproses (Sedang Dikerjakan)</option>
                                                        <option value="Selesai" selected>Selesai (Sudah Dilaksanakan)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Catatan / Laporan Hasil</label>
                                                    <textarea name="follow_up_notes" class="form-control" rows="4" placeholder="Tuliskan laporan tindak lanjut..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top p-3">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success fw-bold px-4">Simpan Laporan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="text-center py-4 text-muted fs-7">
                        <i class="fa-solid fa-paper-plane fs-3 text-secondary opacity-50 d-block mb-2"></i>
                        Belum ada disposisi yang dikirim untuk surat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Kirim Disposisi -->
    <div class="modal fade" id="modalKirimDisposisi" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom p-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-paper-plane text-primary me-2"></i> Form Lembar Disposisi Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dispositions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="letter_id" value="{{ $letter->id }}">

                    <div class="modal-body p-4">
                        <div class="mb-3 bg-light p-3 rounded-3 border">
                            <span class="badge bg-dark font-monospace mb-1">{{ $letter->agenda_number }}</span>
                            <h6 class="fw-bold m-0 text-dark">{{ $letter->subject }}</h6>
                            <small class="text-muted">Dari: {{ $letter->sender }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Pilih Penerima Disposisi (Bisa Lebih Dari Satu) <span class="text-danger">*</span></label>
                            <div class="row g-2 max-h-60 overflow-auto">
                                @foreach($recipients as $rec)
                                    <div class="col-md-6">
                                        <label class="recipient-card d-flex align-items-start gap-2 p-3 rounded-3 border bg-light w-100 mb-0 user-select-none h-100" for="rec{{ $rec->id }}" style="cursor: pointer;">
                                            <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" name="recipients[]" value="{{ $rec->id }}" id="rec{{ $rec->id }}" style="cursor: pointer;">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark fs-7 lh-sm">{{ $rec->name }}</div>
                                                <span class="badge bg-secondary text-white d-inline-block mt-1" style="font-size:0.65rem;">{{ $rec->jabatan ?? $rec->role->display_name }}</span>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <style>
                                .recipient-card {
                                    transition: all 0.15s ease-in-out;
                                }
                                .recipient-card:hover {
                                    background-color: #f1f5f9 !important;
                                    border-color: #94a3b8 !important;
                                }
                                .recipient-card:has(input:checked) {
                                    background-color: #f0f9ff !important;
                                    border-color: #0284c7 !important;
                                    box-shadow: 0 0 0 1px #0284c7;
                                }
                            </style>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Sifat Disposisi <span class="text-danger">*</span></label>
                                <select name="urgency" class="form-select" required>
                                    <option value="Biasa">Biasa</option>
                                    <option value="Penting" selected>Penting</option>
                                    <option value="Rahasia">Rahasia</option>
                                    <option value="Sangat Segera">Sangat Segera</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Batas Waktu Penyelesaian</label>
                                <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Petunjuk / Catatan Disposisi <span class="text-danger">*</span></label>
                            <textarea name="instruction" class="form-control" rows="4" placeholder="Contoh: Pelajari dan fasilitasi rapat koordinasi batas wilayah..." required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-top p-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm" style="background:#0284c7; border:none;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
