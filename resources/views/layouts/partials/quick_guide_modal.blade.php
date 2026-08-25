<!-- Modal Panduan Penggunaan Sistem (User Guide) -->
<div class="modal fade" id="quickGuideModal" tabindex="-1" aria-labelledby="quickGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #0284c7 0%, #0f172a 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.3rem;">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold m-0" id="quickGuideModalLabel">Panduan Cepat & Alur Sistem SIPDK</h5>
                        <small class="text-white-50">Pelajari cara kerja sistem persuratan & disposisi dengan mudah</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <!-- Nav Tabs Peran -->
                <ul class="nav nav-pills nav-fill gap-2 mb-4 bg-white p-2 rounded-3 shadow-sm" id="guideTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-2 fw-semibold py-2" id="alur-umum-tab" data-bs-toggle="tab" data-bs-target="#alur-umum" type="button" role="tab">
                            <i class="fa-solid fa-route me-1"></i> Alur Kerja Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-2 fw-semibold py-2" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-guide" type="button" role="tab">
                            <i class="fa-solid fa-user-shield me-1"></i> Petugas / Admin
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-2 fw-semibold py-2" id="pimpinan-tab" data-bs-toggle="tab" data-bs-target="#pimpinan-guide" type="button" role="tab">
                            <i class="fa-solid fa-user-tie me-1"></i> Lurah / Pimpinan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-2 fw-semibold py-2" id="pelaksana-tab" data-bs-toggle="tab" data-bs-target="#pelaksana-guide" type="button" role="tab">
                            <i class="fa-solid fa-user-check me-1"></i> Staf Pelaksana
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="guideTabsContent">
                    
                    <!-- TAB 1: Alur Kerja Umum -->
                    <div class="tab-pane fade show active" id="alur-umum" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-diagram-project text-primary me-2"></i> 3 Tahapan Utama Persuratan</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 h-100 border bg-primary-subtle text-primary-emphasis">
                                        <div class="fw-bold mb-1"><span class="badge bg-primary text-white me-1">1</span> Catat Surat</div>
                                        <p class="small m-0 text-secondary">Petugas menginput data surat fisik atau digital yang baru masuk kelurahan.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 h-100 border bg-warning-subtle text-warning-emphasis">
                                        <div class="fw-bold mb-1"><span class="badge bg-warning text-dark me-1">2</span> Disposisi</div>
                                        <p class="small m-0 text-secondary">Lurah/Pimpinan membaca surat lalu memberikan arahan kepada staf terkait.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 h-100 border bg-success-subtle text-success-emphasis">
                                        <div class="fw-bold mb-1"><span class="badge bg-success text-white me-1">3</span> Tindak Lanjut</div>
                                        <p class="small m-0 text-secondary">Staf pelaksana mengerjakan instruksi, lalu mengubah status menjadi Selesai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 p-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info text-info me-2"></i> Arti Warna Indikator Status</h6>
                            <div class="d-flex flex-wrap gap-2 pt-1">
                                <span class="badge bg-warning text-dark p-2 px-3 fw-medium"><i class="fa-solid fa-clock me-1"></i> Baru / Menunggu: Belum ada arahan dari Lurah</span>
                                <span class="badge bg-info text-dark p-2 px-3 fw-medium"><i class="fa-solid fa-spinner me-1"></i> Diproses: Sedang dikerjakan oleh staf</span>
                                <span class="badge bg-success text-white p-2 px-3 fw-medium"><i class="fa-solid fa-circle-check me-1"></i> Selesai: Sudah ditindaklanjuti & otomatis terarsip</span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Petugas Admin -->
                    <div class="tab-pane fade" id="admin-guide" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-ol text-primary me-2"></i> Cara Mencatat Surat Masuk Baru:</h6>
                            <ol class="small text-muted mb-0 ps-3 d-flex flex-column gap-2">
                                <li>Klik menu <strong>"Surat Masuk"</strong> atau tombol <strong>"+ Catat Surat Baru"</strong> di Beranda.</li>
                                <li>Isi informasi surat seperti <em>Nomor Surat, Tanggal, Pengirim,</em> dan <em>Perihal</em>.</li>
                                <li>Unggah berkas dokumen (PDF/Gambar) atau gunakan tombol <strong>"Pindai Scanner"</strong> jika ada mesin pemindai.</li>
                                <li>Klik <strong>"Simpan Surat"</strong>. Surat akan langsung masuk ke antrean persetujuan Lurah.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- TAB 3: Lurah / Pimpinan -->
                    <div class="tab-pane fade" id="pimpinan-guide" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-ol text-warning me-2"></i> Cara Memberikan Disposisi / Instruksi:</h6>
                            <ol class="small text-muted mb-0 ps-3 d-flex flex-column gap-2">
                                <li>Buka menu <strong>"Surat Masuk"</strong> atau klik notifikasi surat yang bertanda <strong>"Menunggu Disposisi"</strong>.</li>
                                <li>Klik tombol <strong>"Detail / Disposisi"</strong> untuk membaca isi surat dan lampirannya.</li>
                                <li>Pilih staf penerima tugas (Seksi Pemerintahan, Kessos, dsb.) dan pilih instruksi (misal: <em>Tindak Lanjuti, Pelajari, Hadiri</em>).</li>
                                <li>Ketik catatan tambahan jika ada, lalu klik <strong>"Kirim Disposisi"</strong>.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- TAB 4: Staf Pelaksana -->
                    <div class="tab-pane fade" id="pelaksana-guide" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-ol text-success me-2"></i> Cara Menjalankan & Menyelesaikan Tugas:</h6>
                            <ol class="small text-muted mb-0 ps-3 d-flex flex-column gap-2">
                                <li>Buka menu <strong>"Tugas Saya"</strong> (akan ada angka merah jika ada tugas baru).</li>
                                <li>Klik tugas yang masuk untuk melihat instruksi dan catatan dari Lurah.</li>
                                <li>Setelah pekerjaan/tindak lanjut selesai dilakukan, klik tombol <strong>"Selesaikan Tugas"</strong>.</li>
                                <li>Tambahkan catatan hasil tindak lanjut jika diperlukan, lalu simpan. Surat akan otomatis bertatus <strong>Selesai</strong>.</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 bg-white px-4 py-3 justify-content-between">
                <small class="text-muted"><i class="fa-solid fa-shield-halved me-1"></i> SIPDK Kelurahan Dukuh</small>
                <button type="button" class="btn btn-primary rounded-3 px-4 fw-semibold" data-bs-dismiss="modal">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
