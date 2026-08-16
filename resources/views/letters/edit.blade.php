@extends('layouts.app')

@section('title', 'Edit Surat - ' . $letter->agenda_number)

@section('content')
    <div class="mb-4">
        <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-sm btn-light border rounded-3 fw-bold text-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Batal Edit
        </a>
        <h4 class="fw-bold text-dark m-0">Edit Data Surat Agenda: {{ $letter->agenda_number }}</h4>
    </div>

    <div class="card-custom p-4 max-w-4xl mx-auto">
        <form action="{{ route('letters.update', $letter->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">No. Agenda (Readonly)</label>
                    <input type="text" class="form-control bg-light fw-bold font-monospace" value="{{ $letter->agenda_number }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Nomor Surat Fizik <span class="text-danger">*</span></label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $letter->reference_number) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark fs-7">Kategori Surat <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $letter->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="letter_date" class="form-control" value="{{ old('letter_date', $letter->letter_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark fs-7">Tanggal Diterima <span class="text-danger">*</span></label>
                    <input type="date" name="received_date" class="form-control" value="{{ old('received_date', $letter->received_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark fs-7">Sifat Surat <span class="text-danger">*</span></label>
                    <select name="degree" class="form-select" required>
                        <option value="Biasa" {{ old('degree', $letter->degree) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Penting" {{ old('degree', $letter->degree) == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="Rahasia" {{ old('degree', $letter->degree) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="Sangat Segera" {{ old('degree', $letter->degree) == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark fs-7">Status Surat <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Baru" {{ old('status', $letter->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Dibaca" {{ old('status', $letter->status) == 'Dibaca' ? 'selected' : '' }}>Dibaca</option>
                        <option value="Didisposisi" {{ old('status', $letter->status) == 'Didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                        <option value="Diproses" {{ old('status', $letter->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ old('status', $letter->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Arsip" {{ old('status', $letter->status) == 'Arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Asal Pengirim <span class="text-danger">*</span></label>
                <input type="text" name="sender" class="form-control" value="{{ old('sender', $letter->sender) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Perihal <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject', $letter->subject) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark fs-7">Ringkasan Surat</label>
                <textarea name="summary" class="form-control" rows="3">{{ old('summary', $letter->summary) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark fs-7">Ganti Berkas Dokumen (Opsional)</label>
                <input type="file" name="letter_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah berkas dokumen lama ({{ $letter->file_name }}).</small>
            </div>

            <div class="border-top pt-4 text-end">
                <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-light me-2 fw-semibold">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold px-4 rounded-3 text-dark">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
