@extends('layouts.app')

@section('title', 'Master Kategori Surat')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Master Kategori Surat</h4>
            <small class="text-muted">Kelola klasifikasi jenis dan kategori surat masuk kelurahan</small>
        </div>
        <button class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddCategory" style="background:#0284c7; border:none;">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="card-custom p-4 max-w-4xl">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th>Keterangan Deskripsi</th>
                        <th>Jumlah Surat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td><span class="badge bg-dark font-monospace">{{ $cat->code }}</span></td>
                            <td><span class="fw-bold text-dark">{{ $cat->name }}</span></td>
                            <td><span class="text-muted fs-7">{{ $cat->description ?? '-' }}</span></td>
                            <td><span class="badge bg-primary text-white rounded-pill px-3 py-1">{{ $cat->letters_count }} Surat</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add Category -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Tambah Kategori Surat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('master.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Kategori (Max 5 Huruf) <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase" placeholder="Contoh: UND" required maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Surat Undangan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Keterangan singkat pengelompokan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background:#0284c7; border:none;">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
