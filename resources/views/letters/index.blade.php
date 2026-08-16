@extends('layouts.app')

@section('title', 'Daftar Surat Masuk')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Modul Surat Masuk</h4>
            <small class="text-muted">Pencatatan, pengarsipan, & pencetakan agenda surat masuk kelurahan</small>
        </div>
        <a href="{{ route('letters.create') }}" class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" style="background:#0284c7; border:none;">
            <i class="fa-solid fa-circle-plus me-1"></i> Input Surat Masuk Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('letters.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Cari Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-7" placeholder="No. Agenda, Reference, Pengirim, Perihal..." value="{{ request('search') }}">
                </div>
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

            <div class="col-md-2">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Status Process</label>
                <select name="status" class="form-select bg-light fs-7">
                    <option value="">Semua Status</option>
                    <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Didisposisi" {{ request('status') == 'Didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Sifat Surat</label>
                <select name="degree" class="form-select bg-light fs-7">
                    <option value="">Semua Sifat</option>
                    <option value="Biasa" {{ request('degree') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="Penting" {{ request('degree') == 'Penting' ? 'selected' : '' }}>Penting</option>
                    <option value="Rahasia" {{ request('degree') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                    <option value="Sangat Segera" {{ request('degree') == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold fs-7 rounded-3 py-2">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('letters.index') }}" class="btn btn-outline-secondary fw-bold fs-7 rounded-3 py-2">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 140px;">No. Agenda</th>
                        <th>No. Surat & Pengirim</th>
                        <th>Perihal & Kategori</th>
                        <th>Tgl Terima</th>
                        <th>Sifat</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $letter)
                        <tr>
                            <td>
                                <span class="badge bg-dark text-white font-monospace mb-1">{{ $letter->agenda_number }}</span>
                                <small class="text-muted d-block" style="font-size:0.7rem;">Petugas: {{ $letter->creator->name ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $letter->reference_number }}</div>
                                <div class="text-muted fs-7"><i class="fa-solid fa-building me-1 text-secondary"></i> {{ $letter->sender }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($letter->subject, 40) }}</div>
                                <small class="text-primary font-semibold"><i class="fa-solid fa-tag me-1"></i> {{ $letter->category->name ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $letter->received_date->format('d/m/Y') }}</div>
                                <small class="text-muted">Surat: {{ $letter->letter_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                @php
                                    $degreeClass = match($letter->degree) {
                                        'Biasa' => 'badge-biasa',
                                        'Penting' => 'badge-penting',
                                        'Rahasia' => 'badge-rahasia',
                                        'Sangat Segera' => 'badge-segera',
                                        default => 'badge-biasa'
                                    };
                                @endphp
                                <span class="badge badge-status {{ $degreeClass }}">{{ $letter->degree }}</span>
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
                                <span class="badge badge-status {{ $badgeClass }}">{{ $letter->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-3 dropdown-toggle px-3 fw-semibold fs-7" type="button" data-bs-toggle="dropdown">
                                        Pilihan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                        <li>
                                            <a class="dropdown-item fs-7" href="{{ route('letters.show', $letter->id) }}">
                                                <i class="fa-solid fa-eye text-primary me-2"></i> Detail & Disposisi
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fs-7" href="{{ route('letters.print-agenda', $letter->id) }}" target="_blank">
                                                <i class="fa-solid fa-print text-secondary me-2"></i> Cetak Lembar Agenda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fs-7" href="{{ route('dispositions.print-sheet', $letter->id) }}" target="_blank">
                                                <i class="fa-solid fa-file-invoice text-success me-2"></i> Cetak Lembar Disposisi
                                            </a>
                                        </li>
                                        @if(auth()->user()->isAdmin())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item fs-7 text-warning" href="{{ route('letters.edit', $letter->id) }}">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Surat
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('letters.destroy', $letter->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data surat ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item fs-7 text-danger">
                                                        <i class="fa-solid fa-trash me-2"></i> Hapus Surat
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada data surat masuk yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $letters->links() }}
        </div>
    </div>
@endsection
