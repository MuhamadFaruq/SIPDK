<aside class="sidebar">
    <div class="sidebar-brand d-flex align-items-center justify-content-between" style="padding: 1.5rem 1.25rem;">
        <div class="d-flex align-items-center gap-2 brand-content">
            <div class="sidebar-brand-icon">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="sidebar-brand-text">
                <h5>SIPDK</h5>
                <span>Kelurahan Dukuh</span>
            </div>
        </div>
        <button id="sidebarToggle" class="btn btn-sm text-white border-0 p-1" style="background: transparent;">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <div class="menu-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <div class="menu-label">Persuratan</div>
            <a href="{{ route('letters.index') }}" class="nav-item-link {{ request()->routeIs('letters.*') ? 'active' : '' }}">
                <i class="fa-solid fa-inbox"></i>
                <span>Surat Masuk</span>
            </a>
            <a href="{{ route('outgoing-letters.index') }}" class="nav-item-link {{ request()->routeIs('outgoing-letters.*') ? 'active' : '' }}">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Surat Keluar</span>
            </a>
        @endif

        <div class="menu-label">Tugas & Disposisi</div>
        <a href="{{ route('dispositions.index') }}" class="nav-item-link {{ request()->routeIs('dispositions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>{{ auth()->user()->isPelaksana() ? 'Tugas Saya' : 'Disposisi' }}</span>
            @php
                $pendingCount = \App\Models\Disposition::where('recipient_user_id', auth()->id())->whereIn('status', ['Menunggu', 'Diproses'])->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-danger badge-count">{{ $pendingCount }}</span>
            @endif
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <a href="{{ route('reports.index') }}" class="nav-item-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Laporan</span>
            </a>
        @endif

        @if(auth()->user()->isAdmin())
            <div class="menu-label">Pengaturan Master</div>
            <a href="{{ route('master.users') }}" class="nav-item-link {{ request()->routeIs('master.users') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i>
                <span>Kelola User</span>
            </a>
            <a href="{{ route('master.categories') }}" class="nav-item-link {{ request()->routeIs('master.categories') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Kategori Surat</span>
            </a>
        @endif
    </div>

    <div class="p-3 border-top border-secondary border-opacity-25">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="user-avatar" style="width:32px; height:32px; font-size: 0.8rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="fw-bold text-white fs-7" style="font-size:0.82rem;">{{ Str::limit(auth()->user()->name, 16) }}</div>
                    <span class="badge bg-primary-subtle text-primary" style="font-size:0.65rem;">{{ auth()->user()->role->display_name ?? 'User' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light border-0" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
