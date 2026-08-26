@extends('layouts.app')

@section('title', 'Pencatatan Surat Masuk Baru')

@section('content')
    <div class="mb-4">
        <a href="{{ route('letters.index') }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Surat
        </a>
        <h4 class="fw-bold text-dark m-0">Form Input Surat Masuk Baru</h4>
        <small class="text-muted">Isi data registrasi dan unggah berkas dokumen surat resmi (PDF / JPG / PNG)</small>
    </div>

    <div class="card-custom p-4 max-w-4xl mx-auto">
        <form action="{{ route('letters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">No. Agenda Otomatis</label>
                    <input type="text" name="agenda_number" class="form-control bg-light fw-bold text-primary font-monospace" value="{{ $autoAgendaNumber }}" readonly>
                    <small class="text-muted" style="font-size:0.7rem;">Nomor register agenda otomatis sistem</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Nomor Surat Fizik <span class="text-danger">*</span></label>
                    <input type="text" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" placeholder="Contoh: 005/124/KEC.SKM/2026" value="{{ old('reference_number') }}" required>
                    @error('reference_number') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Kategori Surat <span class="text-danger">*</span></label>
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
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="letter_date" class="form-control @error('letter_date') is-invalid @enderror" value="{{ old('letter_date', date('Y-m-d')) }}" required>
                    @error('letter_date') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Diterima TU <span class="text-danger">*</span></label>
                    <input type="date" name="received_date" class="form-control @error('received_date') is-invalid @enderror" value="{{ old('received_date', date('Y-m-d')) }}" required>
                    @error('received_date') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
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
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Asal Pengirim Surat <span class="text-danger">*</span></label>
                <input type="text" name="sender" class="form-control @error('sender') is-invalid @enderror" placeholder="Contoh: Kecamatan Sukoharjo / Dinas Kesehatan / Pengurus RW 04" value="{{ old('sender') }}" required>
                @error('sender') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Perihal Surat <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Contoh: Undangan Rapat Koordinasi Penataan Batas RT/RW" value="{{ old('subject') }}" required>
                @error('subject') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Ringkasan / Isi Singkat Surat</label>
                <textarea name="summary" class="form-control" rows="3" placeholder="Tuliskan pokok isi surat ringkas...">{{ old('summary') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark fs-7">Unggah Berkas Dokumen (PDF / JPG / PNG) <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="file" name="letter_file" class="form-control @error('letter_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted" style="font-size:0.75rem;">Maksimum ukuran file: 10 MB.</small>
                @error('letter_file') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="border-top pt-4 text-end">
                <a href="{{ route('letters.index') }}" class="btn btn-light me-2 fw-semibold">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm" style="background:#0284c7; border:none;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan & Register Surat
                </button>
            </div>
        </form>
    </div>
@endsection
