@extends('layouts.app')

@section('title', 'Edit Surat Keluar - ' . $letter->agenda_number)

@section('content')
    <div class="mb-4">
        <a href="{{ route('outgoing-letters.show', $letter->id) }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Detail Surat
        </a>
        <h4 class="fw-bold text-dark m-0">Edit Data Surat Keluar</h4>
        <small class="text-muted">Perbarui data agenda atau ganti berkas lampiran naskah dinas</small>
    </div>

    <div class="card-custom p-4 max-w-4xl mx-auto">
        <form action="{{ route('outgoing-letters.update', $letter->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">No. Agenda Keluar</label>
                    <input type="text" class="form-control bg-light fw-bold text-primary font-monospace" value="{{ $letter->agenda_number }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Nomor Berkas / Surat Keluar <span class="text-danger">*</span></label>
                    <input type="text" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number', $letter->reference_number) }}" required>
                    @error('reference_number') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Kategori / Klasifikasi <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $letter->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Surat Keluar <span class="text-danger">*</span></label>
                    <input type="date" name="letter_date" class="form-control @error('letter_date') is-invalid @enderror" value="{{ old('letter_date', $letter->letter_date->format('Y-m-d')) }}" required>
                    @error('letter_date') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Sifat Surat <span class="text-danger">*</span></label>
                    <select name="degree" class="form-select @error('degree') is-invalid @enderror" required>
                        <option value="Biasa" {{ old('degree', $letter->degree) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Penting" {{ old('degree', $letter->degree) == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="Rahasia" {{ old('degree', $letter->degree) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="Sangat Segera" {{ old('degree', $letter->degree) == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                    </select>
                    @error('degree') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Status Surat <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Terkirim" {{ old('status', $letter->status) == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                        <option value="Konsep" {{ old('status', $letter->status) == 'Konsep' ? 'selected' : '' }}>Konsep / Draft</option>
                        <option value="Disetujui" {{ old('status', $letter->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Arsip" {{ old('status', $letter->status) == 'Arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                    @error('status') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Tujuan Surat / Penerima <span class="text-danger">*</span></label>
                <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror" value="{{ old('destination', $letter->destination) }}" required>
                @error('destination') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Perihal Surat <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $letter->subject) }}" required>
                @error('subject') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Ringkasan Isi Surat</label>
                <textarea name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary', $letter->summary) }}</textarea>
                @error('summary') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark fs-7">Ganti Berkas Dokumen (Kosongkan jika tidak ingin diubah)</label>
                @if($letter->file_path)
                    <div class="p-2 mb-2 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                        <small class="text-muted"><i class="fa-solid fa-paperclip me-1"></i> Berkas Saat Ini: {{ $letter->file_name ?? basename($letter->file_path) }}</small>
                        <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="btn btn-sm btn-link p-0 text-primary">Lihat Berkas</a>
                    </div>
                @endif
                <input type="file" name="letter_file" class="form-control @error('letter_file') is-invalid @enderror">
                <small class="text-muted">Maksimal 10 MB (PDF / JPG / PNG / DOCX)</small>
                @error('letter_file') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <hr class="my-4">

            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('outgoing-letters.show', $letter->id) }}" class="btn btn-light border px-4 py-2 fw-bold fs-7">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold fs-7 shadow-sm" style="background:#0284c7; border:none;">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Surat Keluar
                </button>
            </div>
        </form>
    </div>
@endsection
