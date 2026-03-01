<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Scripts & Vite -->
    @vite(['resources/css/modern-clean-theme.css', 'resources/css/user-layout.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
        }

        /* Sidebar Glassmorphism Enhancements */
        .sidebar-modern {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border);
        }

        .sidebar-item.active {
            background: var(--primary);
            color: white !important;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35);
        }
        
        .sidebar-item:hover:not(.active) {
            background: var(--primary-light);
            color: var(--primary) !important;
        }

        .sidebar-brand {
            color: var(--primary) !important;
        }

        /* Top Navbar Style */
        .nav-modern {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        /* Content Body Wrapper */
        .content-body {
            padding: 2rem;
            background-color: var(--background);
        }

        .text-heading {
            color: var(--text-heading);
            font-weight: 700;
        }

        /* Mobile Adjustments */
        @media (max-width: 992px) {
            .sidebar-modern {
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar-modern.show {
                transform: translateX(0);
                box-shadow: var(--shadow-xl);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar-modern" id="sidebar">
            <div class="sidebar-brand">
                <i data-lucide="shield-check"></i>
                <span style="font-size: 1.1rem; letter-spacing: -0.5px;">Lembang Arena</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.bookings.calendar') }}" 
                   class="sidebar-item {{ request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i> Jadwal Booking
                </a>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="sidebar-item {{ request()->routeIs('admin.bookings.*') && !request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
                    <i data-lucide="check-square"></i> Kelola Booking
                </a>

                <a href="{{ route('admin.fields.index') }}" 
                   class="sidebar-item {{ request()->routeIs('admin.fields.*') ? 'active' : '' }}">
                    <i data-lucide="map-pin"></i> Kelola Lapangan
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i> Kelola Pengguna
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="pt-3 border-top">
                    <!-- Contact Info -->
                    <div class="d-flex flex-column gap-2 mb-3 px-2">
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.75rem;">
                            <div class="bg-light p-1 rounded-circle d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">
                                <i data-lucide="mail" style="width: 12px; height: 12px;"></i>
                            </div>
                            <span class="text-truncate">LembangArena@gmail.com</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.75rem;">
                            <div class="bg-light p-1 rounded-circle d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">
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
                        <i data-lucide="activity" class="text-primary" style="width: 24px;"></i>
                        <h5 class="fw-bold mb-0 text-heading">@yield('page_title', 'Dashboard Admin')</h5>
                    </div>
                </div>

                <!-- Right Side Info: Profile similarly to User Side -->
                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-flex flex-column text-end" style="line-height: 1.2;">
                        <span class="fw-bold text-heading small">{{ Auth::user()->name }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Administrator</span>
                    </div>
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
        if (window.innerWidth <= 992 && sidebar && toggle) {
            if (!sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Close sidebar on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
</script>

@yield('scripts')
</body>
</html>