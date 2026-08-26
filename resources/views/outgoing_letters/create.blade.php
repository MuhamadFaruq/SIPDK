@extends('layouts.app')

@section('title', 'Pencatatan Surat Keluar Baru')

@section('content')
    <div class="mb-4">
        <a href="{{ route('outgoing-letters.index') }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Buku Agenda Keluar
        </a>
        <h4 class="fw-bold text-dark m-0">Form Input Surat Keluar Baru</h4>
        <small class="text-muted">Catat registrasi surat keluar, tujuan, dan lampirkan naskah dinas digital</small>
    </div>

    <div class="card-custom p-4 max-w-4xl mx-auto">
        <form action="{{ route('outgoing-letters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">No. Agenda Keluar</label>
                    <input type="text" name="agenda_number" class="form-control bg-light fw-bold text-primary font-monospace" value="{{ $autoAgendaNumber }}" readonly>
                    <small class="text-muted" style="font-size:0.7rem;">Nomor register agenda otomatis sistem</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Nomor Berkas / Surat Keluar <span class="text-danger">*</span></label>
                    <input type="text" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" placeholder="Contoh: 005/012/DKH/2026" value="{{ old('reference_number') }}" required>
                    @error('reference_number') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Kategori / Klasifikasi <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Surat Keluar <span class="text-danger">*</span></label>
                    <input type="date" name="letter_date" class="form-control @error('letter_date') is-invalid @enderror" value="{{ old('letter_date', date('Y-m-d')) }}" required>
                    @error('letter_date') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Sifat Surat <span class="text-danger">*</span></label>
                    <select name="degree" class="form-select @error('degree') is-invalid @enderror" required>
                        <option value="Biasa" {{ old('degree') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Penting" {{ old('degree') == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="Rahasia" {{ old('degree') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="Sangat Segera" {{ old('degree') == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                    </select>
                    @error('degree') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Status Surat <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Terkirim" {{ old('status', 'Terkirim') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                        <option value="Konsep" {{ old('status') == 'Konsep' ? 'selected' : '' }}>Konsep / Draft</option>
                        <option value="Disetujui" {{ old('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Arsip" {{ old('status') == 'Arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                    @error('status') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Tujuan Surat / Penerima <span class="text-danger">*</span></label>
                <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror" placeholder="Contoh: Camat Sukoharjo / Kepala Dinas Kependudukan & Catatan Sipil" value="{{ old('destination') }}" required>
                @error('destination') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Perihal Surat <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Contoh: Laporan Rekapitulasi Pelayanan Administrasi Kependudukan Bulan Agustus" value="{{ old('subject') }}" required>
                @error('subject') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Ringkasan Isi Surat</label>
                <textarea name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" placeholder="Tuliskan ringkasan isi surat secara singkat...">{{ old('summary') }}</textarea>
                @error('summary') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark fs-7">Unggah Berkas Naskah Dinas / Scan (PDF / JPG / PNG / DOCX)</label>
                <input type="file" name="letter_file" class="form-control @error('letter_file') is-invalid @enderror">
                <small class="text-muted">Maksimal ukuran berkas 10 MB</small>
                @error('letter_file') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <hr class="my-4">

            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('outgoing-letters.index') }}" class="btn btn-light border px-4 py-2 fw-bold fs-7">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold fs-7 shadow-sm" style="background:#0284c7; border:none;">
                    <i class="fa-solid fa-save me-1"></i> Simpan Surat Keluar
                </button>
            </div>
        </form>
    </div>
@endsection
