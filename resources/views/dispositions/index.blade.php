@extends('layouts.app')

@section('title', 'Daftar Disposisi Surat')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Modul Disposisi Surat</h4>
            <small class="text-muted">Kelola alur disposisi, penugasan, dan pemantauan tindak lanjut surat</small>
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
                        <th>No. Agenda / Surat</th>
                        <th>{{ $tab === 'sent' ? 'Penerima Disposisi' : 'Pengirim Disposisi' }}</th>
                        <th>Instruksi Disposisi</th>
                        <th>Urgensi & Batas</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
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
                                <p class="m-0 fs-7 text-dark">"{{ Str::limit($disp->instruction, 45) }}"</p>
                                @if($disp->follow_up_notes)
                                    <small class="text-success fw-bold d-block mt-1"><i class="fa-solid fa-check me-1"></i> Result: {{ Str::limit($disp->follow_up_notes, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-status badge-penting mb-1">{{ $disp->urgency }}</span>
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
                                <a href="{{ route('letters.show', $disp->letter->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold fs-7">
                                    Detail Surat
                                </a>
                            </td>
                        </tr>
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
@endsection
