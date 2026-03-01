<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lembang Arena')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/modern-clean-theme.css', 'resources/css/user-layout.css'])


    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body>

    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar-modern" id="sidebar">
            <div class="sidebar-brand">
                <i data-lucide="futbol"></i>
                <span style="font-size: 1.1rem; letter-spacing: -0.5px;">Lembang Arena</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('user.dashboard') }}" 
                   class="sidebar-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
                <a href="{{ route('user.fields.index') }}" 
                   class="sidebar-item {{ request()->routeIs('user.fields.*') ? 'active' : '' }}">
                    <i data-lucide="grid"></i> Lapangan
                </a>
                <a href="{{ route('user.bookings.calendar') }}" 
                   class="sidebar-item {{ request()->routeIs('user.bookings.calendar') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i> Jadwal
                </a>
                <a href="{{ route('user.bookings.index') }}" 
                   class="sidebar-item {{ request()->routeIs('user.bookings.index') && !request()->routeIs('user.bookings.calendar') ? 'active' : '' }}">
                    <i data-lucide="clipboard-list"></i> 
                    <span class="flex-grow-1">Pesanan</span>
                    @php
                        $activeCount = Auth::user()->bookings()->whereIn('status', ['pending', 'confirmed'])->where('booking_time', '>=', now())->count();
                    @endphp
                    @if($activeCount > 0)
                        <span class="badge rounded-pill bg-primary px-2 py-1" style="font-size: 0.65rem;">{{ $activeCount }}</span>
                    @endif
                </a>
            </nav>
 
            <div class="sidebar-footer">
                <div class="pt-3 border-top">
                    <!-- Contact Info -->
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.8rem;">
                            <div class="bg-light p-1 rounded-circle">
                                <i data-lucide="mail" style="width: 12px; height: 12px;"></i>
                            </div>
                            <span class="text-truncate">LembangArena@gmail.com</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.8rem;">
                            <div class="bg-light p-1 rounded-circle">
                                <i data-lucide="phone" style="width: 12px; height: 12px;"></i>
                            </div>
                            <span>+62 812 3456 7890</span>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="sidebar-logout">
                            <i data-lucide="log-out" style="width: 18px;"></i> 
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper">
            <header class="nav-modern px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-modern d-lg-none p-2 border-0" id="sidebarToggle">
                        <i data-lucide="menu"></i>
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="calendar" class="text-primary" style="width: 24px;"></i>
                        <h5 class="fw-bold mb-0 text-heading">@yield('page_title', 'Dashboard')</h5>
                    </div>
                </div>

                <!-- User Name in Top Navbar -->
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold text-heading d-none d-sm-inline-block small">{{ Auth::user()->name }}</span>
                    <div class="bg-light text-primary border rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i data-lucide="user" style="width: 18px;"></i>
                    </div>
                </div>
            </header>

            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Lucide
        lucide.createIcons();

        // Sidebar Toggle for Mobile
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        
        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>