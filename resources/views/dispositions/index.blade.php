@extends('layouts.app')

@section('title', 'Daftar Disposisi Surat')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Modul Disposisi Surat</h4>
            <small class="text-muted">Kelola alur disposisi berjenjang, penugasan, dan pemantauan tindak lanjut surat</small>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link fw-bold me-2 {{ $tab === 'received' ? 'active bg-primary' : 'bg-light text-dark' }}" href="{{ route('dispositions.index', ['tab' => 'received']) }}">
                <i class="fa-solid fa-inbox me-1"></i> Disposisi Masuk (Diterima)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold {{ $tab === 'sent' ? 'active bg-primary' : 'bg-light text-dark' }}" href="{{ route('dispositions.index', ['tab' => 'sent']) }}">
                <i class="fa-solid fa-paper-plane me-1"></i> Disposisi Terkirim
            </a>
        </li>
    </ul>

    <!-- Filter Bar -->
    <div class="card-custom p-4 mb-4">
        <form action="{{ route('dispositions.index') }}" method="GET" class="row g-3">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Status Disposisi</label>
                <select name="status" class="form-select bg-light fs-7">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 text-dark mb-1">Tingkat Urgensi</label>
                <select name="urgency" class="form-select bg-light fs-7">
                    <option value="">Semua Urgensi</option>
                    <option value="Biasa" {{ request('urgency') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="Penting" {{ request('urgency') == 'Penting' ? 'selected' : '' }}>Penting</option>
                    <option value="Rahasia" {{ request('urgency') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                    <option value="Sangat Segera" {{ request('urgency') == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold fs-7 rounded-3 py-2">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('dispositions.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary fw-bold fs-7 rounded-3 py-2">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Dispositions Table -->
    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th width="20%">No. Agenda / Surat</th>
                        <th width="18%">{{ $tab === 'sent' ? 'Penerima Disposisi' : 'Pengirim Disposisi' }}</th>
                        <th width="24%">Instruksi Disposisi</th>
                        <th width="12%">Urgensi & Batas</th>
                        <th width="10%">Status</th>
                        <th width="16%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispositions as $disp)
                        <tr>
                            <td>
                                <span class="badge bg-dark font-monospace mb-1">{{ $disp->letter->agenda_number }}</span>
                                <a href="{{ route('letters.show', $disp->letter->id) }}" class="fw-bold text-dark d-block text-decoration-none">
                                    {{ Str::limit($disp->letter->subject, 35) }}
                                </a>
                                @if($disp->parent_id)
                                    <span class="badge bg-info-subtle text-info mt-1" style="font-size:0.68rem;">
                                        <i class="fa-solid fa-code-fork me-1"></i> Disposisi Lanjutan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($tab === 'sent')
                                    <div class="fw-bold text-dark">{{ $disp->recipient->name }}</div>
                                    <small class="text-muted">{{ $disp->recipientDepartment->name ?? $disp->recipient->role->display_name }}</small>
                                @else
                                    <div class="fw-bold text-dark">{{ $disp->sender->name }}</div>
                                    <small class="text-muted">{{ $disp->sender->jabatan ?? $disp->sender->role->display_name }}</small>
                                @endif
                            </td>
                            <td>
                                <p class="m-0 fs-7 text-dark fst-italic">"{{ Str::limit($disp->instruction, 50) }}"</p>
                                @if($disp->follow_up_notes)
                                    <small class="text-success fw-bold d-block mt-1"><i class="fa-solid fa-check me-1"></i> Hasil: {{ Str::limit($disp->follow_up_notes, 35) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $urgencyBadge = match($disp->urgency) {
                                        'Penting' => 'badge-penting',
                                        'Rahasia' => 'bg-danger text-white',
                                        'Sangat Segera' => 'bg-danger text-white',
                                        default => 'badge-baru'
                                    };
                                @endphp
                                <span class="badge {{ $urgencyBadge }} mb-1">{{ $disp->urgency }}</span>
                                @if($disp->due_date)
                                    <small class="text-danger fw-semibold d-block"><i class="fa-regular fa-clock me-1"></i> {{ $disp->due_date->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($disp->status) {
                                        'Menunggu' => 'bg-warning text-dark',
                                        'Diproses' => 'bg-info text-dark',
                                        'Selesai' => 'bg-success text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }} rounded-pill px-3 py-1">{{ $disp->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('letters.show', $disp->letter->id) }}" class="btn btn-light border" title="Detail Surat">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>

                                    @if($tab === 'received' && $disp->status !== 'Selesai')
                                        <!-- Teruskan Disposisi (Cascading) -->
                                        <button type="button" class="btn btn-light border text-info" title="Teruskan Disposisi ke Staf" data-bs-toggle="modal" data-bs-target="#modalForward{{ $disp->id }}">
                                            <i class="fa-solid fa-share-nodes"></i>
                                        </button>

                                        <!-- Laporkan Selesai -->
                                        <button type="button" class="btn btn-light border text-success" title="Laporkan Selesai" data-bs-toggle="modal" data-bs-target="#modalFollowUp{{ $disp->id }}">
                                            <i class="fa-solid fa-check-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if($tab === 'received')
                            <!-- Modal Teruskan Disposisi -->
                            <div class="modal fade text-start" id="modalForward{{ $disp->id }}" tabindex="-1">
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
                                                <div class="p-3 bg-light rounded-3 border mb-3">
                                                    <span class="badge bg-dark font-monospace mb-1">{{ $disp->letter->agenda_number }}</span>
                                                    <h6 class="fw-bold text-dark m-0">{{ $disp->letter->subject }}</h6>
                                                    <small class="text-muted d-block mt-1"><strong>Instruksi Sebelumnya ({{ $disp->sender->name }}):</strong> "{{ $disp->instruction }}"</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Pilih Penerima Terusan (Bisa Lebih Dari Satu) <span class="text-danger">*</span></label>
                                                    <div class="row g-2 max-h-60 overflow-auto">
                                                        @foreach($recipients as $rec)
                                                            @if($rec->id !== auth()->id())
                                                                <div class="col-md-6">
                                                                    <label class="recipient-card d-flex align-items-start gap-2 p-3 rounded-3 border bg-light w-100 mb-0 user-select-none h-100" for="fwd_{{ $disp->id }}_{{ $rec->id }}" style="cursor: pointer;">
                                                                        <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" name="recipients[]" value="{{ $rec->id }}" id="fwd_{{ $disp->id }}_{{ $rec->id }}" style="cursor: pointer;">
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

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-dark">Sifat Disposisi</label>
                                                        <select name="urgency" class="form-select">
                                                            <option value="Biasa" {{ $disp->urgency === 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                                            <option value="Penting" {{ $disp->urgency === 'Penting' ? 'selected' : '' }}>Penting</option>
                                                            <option value="Rahasia" {{ $disp->urgency === 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                                            <option value="Sangat Segera" {{ $disp->urgency === 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-dark">Batas Waktu Penyelesaian</label>
                                                        <input type="date" name="due_date" class="form-control" value="{{ $disp->due_date ? $disp->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+3 days')) }}">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Petunjuk / Instruksi Tambahan <span class="text-danger">*</span></label>
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

                            <!-- Modal Follow Up (Laporkan Selesai) -->
                            <div class="modal fade text-start" id="modalFollowUp{{ $disp->id }}" tabindex="-1">
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
                                                    <textarea name="follow_up_notes" class="form-control" rows="4" placeholder="Tuliskan hasil kerja yang sudah diselesaikan..." required>{{ $disp->follow_up_notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top p-3">
                                                <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success fw-bold px-4 py-2">
                                                    <i class="fa-solid fa-save me-2"></i> Simpan Laporan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-paper-plane fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada disposisi {{ $tab === 'sent' ? 'terkirim' : 'masuk' }} yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $dispositions->links() }}
        </div>
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
@endsection
