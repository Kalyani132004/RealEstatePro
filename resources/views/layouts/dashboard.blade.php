<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') | RealEstatePro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    <script>
        (function () {
            var savedTheme = localStorage.getItem('rep-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    @stack('styles')
</head>
<body class="rep-dash-body">

    <div class="rep-page-loader" id="repPageLoader">
        <div class="rep-loader-logo">RealEstate<span style="color:var(--rep-accent)">Pro</span></div>
    </div>

    <div class="rep-toast-container" id="repToastContainer"></div>

    <div class="rep-dash-wrapper">

        {{-- Sidebar --}}
        <aside class="rep-dash-sidebar" id="repDashSidebar">
            <div class="rep-dash-sidebar-brand">
                <a href="{{ route('home') }}" class="rep-h4 text-decoration-none" style="color:#fff;">
                    RealEstate<span style="color:var(--rep-accent)">Pro</span>
                </a>
                <button type="button" class="btn-close btn-close-white d-lg-none" id="repSidebarClose" aria-label="Close"></button>
            </div>

            <nav class="rep-dash-nav">
                @yield('sidebar')
            </nav>

            <div class="rep-dash-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rep-dash-nav-link w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="rep-dash-backdrop" id="repDashBackdrop"></div>

        {{-- Main --}}
        <div class="rep-dash-main">
            {{-- Topbar --}}
            <header class="rep-dash-topbar">
                <button type="button" class="btn rep-dash-menu-btn d-lg-none" id="repSidebarOpen" aria-label="Open menu">
                    <i class="bi bi-list fs-3"></i>
                </button>

                <h1 class="rep-h4 mb-0 d-none d-md-block">@yield('page-title', 'Dashboard')</h1>

                <div class="d-flex align-items-center gap-3 ms-auto">
                    <button type="button" class="rep-theme-toggle" id="repThemeToggle" aria-label="Toggle dark mode">
                        <i class="bi bi-moon-stars-fill" style="color:var(--rep-primary)"></i>
                    </button>

                    <div class="dropdown">
                        <button class="btn d-flex align-items-center gap-2 border-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rep-avatar-sm">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <span class="d-none d-md-inline rep-small fw-semibold">{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down rep-small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rep-card p-2 mt-2">
                            <li><a class="dropdown-item rounded" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item rounded" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Visit Site</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <div class="rep-dash-content">
                @yield('content')
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('success')), 'success'); });</script>
    @endif
    @if (session('error'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('error')), 'danger'); });</script>
    @endif
    @if ($errors->any())
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json($errors->first()), 'danger'); });</script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
