@extends('layouts.app')

@section('title', 'Kelola User System')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Pengaturan Master User & Pengguna</h4>
            <small class="text-muted">Kelola akun pegawai, hak akses role, dan seksi/bidang kedinasan</small>
        </div>
        <button class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddUser" style="background:#0284c7; border:none;">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah User Baru
        </button>
    </div>

    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th>Pegawai</th>
                        <th>NIP & Jabatan</th>
                        <th>Role Hak Akses</th>
                        <th>Seksi / Bidang</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:36px; height:36px;">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        <small class="text-muted">{{ $u->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $u->jabatan ?? '-' }}</div>
                                <small class="text-muted font-monospace">NIP: {{ $u->nip ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary text-white">{{ $u->role->display_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $u->department->name ?? 'Sekretariat' }}</span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-sm btn-light border fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('master.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger fw-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit User -->
                        <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title fw-bold">Edit User: {{ $u->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('master.users.update', $u->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">NIP</label>
                                                <input type="text" name="nip" class="form-control" value="{{ $u->nip }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Jabatan</label>
                                                <input type="text" name="jabatan" class="form-control" value="{{ $u->jabatan }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Role Hak Akses</label>
                                                <select name="role_id" class="form-select" required>
                                                    @foreach($roles as $r)
                                                        <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->display_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Seksi / Department</label>
                                                <select name="department_id" class="form-select">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach($departments as $d)
                                                        <option value="{{ $d->id }}" {{ $u->department_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Ganti Password (Opsional)</label>
                                                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Status Akun</label>
                                                <select name="is_active" class="form-select">
                                                    <option value="1" {{ $u->is_active ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Add User -->
    <div class="modal fade" id="modalAddUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Tambah User Pegawai Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('master.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso, S.STP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="user@kelurahan.go.id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Default <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control" placeholder="19850101 ...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Staff Pelaksana">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role Hak Akses <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Seksi / Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background:#0284c7; border:none;">Tambah User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
