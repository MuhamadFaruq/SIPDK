@extends('layouts.app')

@section('title', 'Detail Surat Keluar - ' . $letter->agenda_number)

@section('content')
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <a href="{{ route('outgoing-letters.index') }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Buku Agenda
            </a>
            <h4 class="fw-bold text-dark m-0">Detail Surat Keluar: <span class="text-primary font-monospace">{{ $letter->agenda_number }}</span></h4>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('outgoing-letters.print-agenda', $letter->id) }}" target="_blank" class="btn btn-outline-secondary fw-bold rounded-3 fs-7">
                <i class="fa-solid fa-print me-1"></i> Cetak Lembar Agenda
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('outgoing-letters.edit', $letter->id) }}" class="btn btn-light border fw-bold rounded-3 fs-7">
                    <i class="fa-solid fa-pen text-warning me-1"></i> Edit Data
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: Letter Info & Metadata -->
        <div class="col-lg-5">
            <div class="card-custom p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div>
                        <span class="badge bg-dark text-white font-monospace mb-1 fs-7">{{ $letter->agenda_number }}</span>
                        <h5 class="fw-bold text-dark m-0">{{ $letter->subject }}</h5>
                    </div>
                    <div>
                        @php
                            $statusClass = match($letter->status) {
                                'Konsep' => 'bg-warning text-dark',
                                'Disetujui' => 'bg-info text-dark',
                                'Terkirim' => 'bg-success text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} fs-7 px-3 py-2">{{ $letter->status }}</span>
                    </div>
                </div>

                <div class="row g-3 fs-7 mb-3">
                    <div class="col-12">
                        <small class="text-muted d-block">Tujuan Surat / Penerima:</small>
                        <span class="fw-bold text-dark fs-6">{{ $letter->destination }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Nomor Surat Keluar:</small>
                        <span class="fw-bold text-dark">{{ $letter->reference_number }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Kategori / Klasifikasi:</small>
                        <span class="badge bg-secondary-subtle text-secondary">{{ $letter->category->name }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Tanggal Surat Keluar:</small>
                        <span class="fw-bold text-dark">{{ $letter->letter_date->isoFormat('D MMMM Y') }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Sifat Surat:</small>
                        <span class="badge {{ $letter->degree === 'Biasa' ? 'bg-light text-dark border' : 'bg-danger-subtle text-danger' }}">
                            {{ $letter->degree }}
                        </span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Dicatat Oleh:</small>
                        <span class="text-dark">{{ $letter->creator->name ?? 'System Admin' }} ({{ $letter->created_at->isoFormat('D MMM Y, HH:mm') }})</span>
                    </div>
                </div>

                @if($letter->summary)
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="fw-bold text-dark d-block mb-1">Ringkasan / Isi Surat:</small>
                        <p class="text-secondary m-0 fs-7">{{ $letter->summary }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Document Preview -->
        <div class="col-lg-7">
            <div class="card-custom p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="fw-bold text-dark m-0"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Pratinjau Berkas Dokumen</h6>
                    @if($letter->file_path)
                        <a href="{{ route('outgoing-letters.file', $letter->id) }}" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                            <i class="fa-solid fa-download me-1"></i> Unduh Berkas
                        </a>
                    @endif
                </div>

                @if($letter->file_path)
                    @php
                        $extension = strtolower(pathinfo($letter->file_path, PATHINFO_EXTENSION));
                    @endphp

                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                        <div class="text-center p-3 bg-light rounded-3">
                            <img src="{{ route('outgoing-letters.file', $letter->id) }}" class="img-fluid rounded shadow-sm" alt="Berkas Surat Keluar" style="max-height: 600px;">
                        </div>
                    @elseif($extension === 'pdf')
                        <iframe src="{{ route('outgoing-letters.file', $letter->id) }}" class="w-100 rounded-3 border" style="height: 650px;"></iframe>
                    @else
                        <div class="text-center py-5 bg-light rounded-3">
                            <i class="fa-solid fa-file-lines fs-1 text-primary mb-3"></i>
                            <h6 class="fw-bold text-dark">Berkas Dokumen Lampiran</h6>
                            <p class="text-muted fs-7">Format berkas: <strong>{{ strtoupper($extension) }}</strong></p>
                            <a href="{{ route('outgoing-letters.file', $letter->id) }}" target="_blank" class="btn btn-primary fw-bold">
                                <i class="fa-solid fa-download me-1"></i> Buka / Unduh Berkas
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted bg-light rounded-3">
                        <i class="fa-regular fa-file-excel fs-1 mb-3 text-secondary opacity-50"></i>
                        <p class="m-0 fs-7">Tidak ada berkas scan/dokumen digital yang dilampirkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
