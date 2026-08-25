<nav class="top-navbar">
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <button id="mobileSidebarToggle" class="btn btn-light d-lg-none rounded-3 px-2 py-1 border shadow-xs text-dark" type="button" title="Buka Menu">
            <i class="fa-solid fa-bars fs-6"></i>
        </button>
        <h5 class="fw-bold m-0 text-dark">@yield('title', 'SIPDK System')</h5>
    </div>

    <div class="d-flex align-items-center gap-2 gap-md-3">
        <!-- Font Size Selector (Ramah Lansia & Penglihatan) -->
        <div class="btn-group font-size-control bg-white rounded-3 shadow-xs border p-1" role="group" aria-label="Ukuran Teks">
            <button type="button" class="btn btn-sm btn-light py-1 px-2 border-0 fw-semibold active" id="btnFontNormal" onclick="setFontSize('normal')" title="Ukuran Teks Normal">
                <i class="fa-solid fa-font fs-7"></i> A
            </button>
            <button type="button" class="btn btn-sm btn-light py-1 px-2 border-0 fw-semibold" id="btnFontMd" onclick="setFontSize('md')" title="Ukuran Teks Besar">
                A<i class="fa-solid fa-plus fs-8"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light py-1 px-2 border-0 fw-semibold" id="btnFontLg" onclick="setFontSize('lg')" title="Ukuran Teks Sangat Besar">
                A<i class="fa-solid fa-plus fs-8"></i><i class="fa-solid fa-plus fs-8"></i>
            </button>
        </div>

        <!-- Quick Guide Button -->
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center gap-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#quickGuideModal" title="Lihat Panduan dan Alur Sistem">
            <i class="fa-solid fa-circle-question"></i>
            <span class="d-none d-md-inline">Panduan Sistem</span>
        </button>

        <!-- Role Badge -->
        <div class="user-profile-badge">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="text-end">
                <div class="fw-bold text-dark fs-7" style="font-size:0.95rem;">{{ auth()->user()->name }}</div>
                <small class="text-muted d-block" style="font-size:0.8rem;">{{ auth()->user()->role->display_name }}</small>
            </div>
        </div>
    </div>
</nav>
