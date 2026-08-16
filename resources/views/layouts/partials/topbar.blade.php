<nav class="top-navbar">
    <div class="d-flex align-items-center gap-3">
        <h5 class="fw-bold m-0 text-dark">@yield('title', 'SIPDK System')</h5>
    </div>

    <div class="d-flex align-items-center gap-3">
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
