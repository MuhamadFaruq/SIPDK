@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Persuratan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Laporan Rekapitulasi Agenda & Disposisi</h4>
            <small class="text-muted">Generate laporan persuratan berkala untuk pertanggungjawaban kedinasan</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export-csv', request()->all()) }}" class="btn btn-outline-success fw-bold rounded-3 fs-7">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel (.CSV)
            </a>
            <a href="{{ route('reports.print', request()->all()) }}" target="_blank" class="btn btn-primary fw-bold rounded-3 fs-7 shadow-sm" style="background:#0284c7; border:none;">
                <i class="fa-solid fa-print me-1"></i> Cetak Laporan PDF
            </a>
        </div>
    </div>

    <!-- Date Range & Category Filter -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Dari Tanggal</label>
                <input type="date" name="date_start" class="form-control bg-light fs-7" value="{{ $startDate }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Sampai Tanggal</label>
                <input type="date" name="date_end" class="form-control bg-light fs-7" value="{{ $endDate }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Filter Kategori</label>
                <select name="category_id" class="form-select bg-light fs-7">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold fs-7 rounded-3 py-2">
                    <i class="fa-solid fa-filter me-1"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-custom p-3 text-center">
                <small class="text-muted fw-semibold">Total Surat Masuk</small>
                <h3 class="fw-bold text-dark m-0">{{ $totalCount }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-3 text-center border-start border-4 border-success">
                <small class="text-muted fw-semibold">Surat Selesai</small>
                <h3 class="fw-bold text-success m-0">{{ $selesaiCount }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-3 text-center border-start border-4 border-warning">
                <small class="text-muted fw-semibold">Dalam Proses Disposisi</small>
                <h3 class="fw-bold text-warning m-0">{{ $diprosesCount }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-3 text-center border-start border-4 border-primary">
                <small class="text-muted fw-semibold">Surat Baru</small>
                <h3 class="fw-bold text-primary m-0">{{ $baruCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Report Preview Table -->
    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size:0.85rem;">
                <thead class="table-light text-uppercase fw-bold">
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th>No. Agenda</th>
                        <th>No. Surat & Tgl</th>
                        <th>Pengirim</th>
                        <th>Perihal Surat</th>
                        <th>Kategori</th>
                        <th>Disposisi Kepada</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $index => $l)
                        <tr>
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td><span class="font-monospace fw-bold text-primary">{{ $l->agenda_number }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $l->reference_number }}</div>
                                <small class="text-muted">{{ $l->received_date->format('d/m/Y') }}</small>
                            </td>
                            <td>{{ $l->sender }}</td>
                            <td><strong>{{ $l->subject }}</strong></td>
                            <td>{{ $l->category->name ?? '-' }}</td>
                            <td>
                                @if($l->dispositions->count() > 0)
                                    <ul class="m-0 p-0 ps-3">
                                        @foreach($l->dispositions as $d)
                                            <li><small>{{ $d->recipient->name }} ({{ $d->status }})</small></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <small class="text-muted">Belum ada disposisi</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-status badge-selesai">{{ $l->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Tidak ada data surat pada periode tanggal yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
