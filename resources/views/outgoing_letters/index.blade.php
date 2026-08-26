@extends('layouts.app')

@section('title', 'Buku Agenda Surat Keluar')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Buku Agenda Surat Keluar</h4>
            <small class="text-muted">Pencatatan, pengarsipan, dan pencetakan buku agenda surat keluar kelurahan</small>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('outgoing-letters.create') }}" class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" style="background:#0284c7; border:none;">
                <i class="fa-solid fa-circle-plus me-1"></i> Catat Surat Keluar Baru
            </a>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('outgoing-letters.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Cari Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-7" placeholder="No. Agenda, No. Berkas, Tujuan, Perihal..." value="{{ request('search') }}">
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
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Status Surat</label>
                <select name="status" class="form-select bg-light fs-7">
                    <option value="">Semua Status</option>
                    <option value="Konsep" {{ request('status') == 'Konsep' ? 'selected' : '' }}>Konsep</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Terkirim" {{ request('status') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                    <option value="Arsip" {{ request('status') == 'Arsip' ? 'selected' : '' }}>Arsip</option>
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
                    <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status', 'degree']))
                    <a href="{{ route('outgoing-letters.index') }}" class="btn btn-light border fw-bold fs-7 rounded-3 py-2" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Letters Table Card -->
    <div class="card-custom p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="fs-7 text-muted">Menampilkan <strong>{{ $letters->total() }}</strong> arsip surat keluar</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                <thead class="table-light">
                    <tr>
                        <th width="15%">No. Agenda & Tgl</th>
                        <th width="18%">Nomor Surat Keluar</th>
                        <th width="18%">Tujuan Surat</th>
                        <th width="24%">Perihal</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $letter)
                        <tr>
                            <td>
                                <span class="badge bg-dark font-monospace mb-1">{{ $letter->agenda_number }}</span>
                                <small class="text-muted d-block"><i class="fa-regular fa-calendar me-1"></i> {{ $letter->letter_date->isoFormat('D MMM Y') }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $letter->reference_number }}</span>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.7rem;">{{ $letter->category->name }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $letter->destination }}</span>
                            </td>
                            <td>
                                <a href="{{ route('outgoing-letters.show', $letter->id) }}" class="text-dark fw-bold text-decoration-none d-block">
                                    {{ Str::limit($letter->subject, 45) }}
                                </a>
                                @if($letter->degree !== 'Biasa')
                                    <span class="badge bg-danger-subtle text-danger" style="font-size:0.65rem;">{{ $letter->degree }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($letter->status) {
                                        'Konsep' => 'bg-warning text-dark',
                                        'Disetujui' => 'bg-info text-dark',
                                        'Terkirim' => 'bg-success text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1 fs-7">{{ $letter->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('outgoing-letters.show', $letter->id) }}" class="btn btn-light border" title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('outgoing-letters.print-agenda', $letter->id) }}" target="_blank" class="btn btn-light border" title="Cetak Lembar Agenda">
                                        <i class="fa-solid fa-print text-secondary"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('outgoing-letters.edit', $letter->id) }}" class="btn btn-light border" title="Edit Surat">
                                            <i class="fa-solid fa-pen text-warning"></i>
                                        </a>
                                        <form action="{{ route('outgoing-letters.destroy', $letter->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat keluar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light border" title="Hapus Surat">
                                                <i class="fa-solid fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-paper-plane fs-1 mb-3 text-secondary opacity-50"></i><br>
                                Belum ada surat keluar yang dicatat.
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
