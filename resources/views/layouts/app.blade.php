<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPDK') - Sistem Informasi Persuratan & Disposisi Kelurahan</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <script>
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-toggled');
        }
        const savedFontSize = localStorage.getItem('sipdk-font-size');
        if (savedFontSize === 'md') {
            document.body.classList.add('font-size-md');
        } else if (savedFontSize === 'lg') {
            document.body.classList.add('font-size-lg');
        }
    </script>

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Topbar -->
        @include('layouts.partials.topbar')

        <!-- Body Content -->
        <main class="content-body">
            <!-- Flash Messages -->
            @include('layouts.partials.alerts')

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Quick Guide Modal -->
    @include('layouts.partials.quick_guide_modal')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts -->
    <script>
        // Sidebar Toggle (Desktop & Mobile)
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        
        function toggleSidebarHandler() {
            document.body.classList.toggle('sidebar-toggled');
            localStorage.setItem('sidebar-collapsed', document.body.classList.contains('sidebar-toggled'));
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebarHandler);
        }
        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', toggleSidebarHandler);
        }

        // Accessibility: Font Size Switcher
        function setFontSize(size) {
            document.body.classList.remove('font-size-md', 'font-size-lg');
            
            const btnNormal = document.getElementById('btnFontNormal');
            const btnMd = document.getElementById('btnFontMd');
            const btnLg = document.getElementById('btnFontLg');
            
            if (btnNormal) btnNormal.classList.remove('active');
            if (btnMd) btnMd.classList.remove('active');
            if (btnLg) btnLg.classList.remove('active');

            if (size === 'md') {
                document.body.classList.add('font-size-md');
                localStorage.setItem('sipdk-font-size', 'md');
                if (btnMd) btnMd.classList.add('active');
            } else if (size === 'lg') {
                document.body.classList.add('font-size-lg');
                localStorage.setItem('sipdk-font-size', 'lg');
                if (btnLg) btnLg.classList.add('active');
            } else {
                localStorage.setItem('sipdk-font-size', 'normal');
                if (btnNormal) btnNormal.classList.add('active');
            }
        }

        // Sync button active state on initial load
        document.addEventListener('DOMContentLoaded', function() {
            const currentSize = localStorage.getItem('sipdk-font-size') || 'normal';
            setFontSize(currentSize);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
