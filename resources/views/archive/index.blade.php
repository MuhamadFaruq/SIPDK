@extends('layouts.app')

@section('title', 'Arsip Digital Persuratan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Modul Arsip Digital Surat</h4>
            <small class="text-muted">Pencarian cepat & temu balik dokumen arsip persuratan elektronik kelurahan</small>
        </div>
    </div>

    <!-- Filter Search Box -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('archive.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Pencarian Cerdas (Smart Search)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-7" placeholder="Kata kunci, No. Surat, Pengirim, Perihal, Ringkasan..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Tahun Arsip</label>
                <select name="year" class="form-select bg-light fs-7">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Bulan Diterima</label>
                <select name="month" class="form-select bg-light fs-7">
                    <option value="">Semua Bulan</option>
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Kategori Surat</label>
                <select name="category_id" class="form-select bg-light fs-7">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold fs-7 rounded-3 py-2 shadow-sm" style="background:#0284c7; border:none;">
                    <i class="fa-solid fa-search me-1"></i> Cari Arsip
                </button>
            </div>
        </form>
    </div>

    <!-- Archive Grid Cards -->
    <div class="row g-4">
        @forelse($letters as $letter)
            <div class="col-md-6 col-xl-4">
                <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-dark font-monospace text-white">{{ $letter->agenda_number }}</span>
                            <span class="badge bg-light text-dark border">{{ $letter->category->name ?? '-' }}</span>
                        </div>

                        <h6 class="fw-bold text-dark mb-2 text-truncate" title="{{ $letter->subject }}">
                            {{ $letter->subject }}
                        </h6>

                        <div class="fs-7 text-muted mb-3">
                            <div><i class="fa-solid fa-building me-1 text-secondary"></i> {{ Str::limit($letter->sender, 30) }}</div>
                            <div><i class="fa-regular fa-calendar me-1 text-secondary"></i> {{ $letter->received_date->isoFormat('D MMMM Y') }}</div>
                            <div><i class="fa-solid fa-hashtag me-1 text-secondary"></i> {{ $letter->reference_number }}</div>
                        </div>

                        @if($letter->summary)
                            <p class="fs-7 text-dark bg-light p-2 rounded border mb-3 text-truncate-2">
                                "{{ $letter->summary }}"
                            </p>
                        @endif
                    </div>

                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <span class="badge badge-status badge-selesai">{{ $letter->status }}</span>
                        <div class="d-flex gap-1">
                            <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-sm btn-light border fw-bold fs-7">
                                <i class="fa-solid fa-eye text-primary"></i> Detail
                            </a>
                            @if($letter->file_path && Storage::disk('public')->exists($letter->file_path))
                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger fw-bold fs-7" title="Unduh Berkas PDF">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card-custom p-5">
                    <i class="fa-solid fa-box-archive fs-1 text-secondary opacity-50 d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">Arsip Surat Tidak Ditemukan</h5>
                    <p class="text-muted fs-7">Coba ubah kata kunci atau kata filter pencarian arsip Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $letters->links() }}
    </div>
@endsection
