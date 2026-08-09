<nav class="navbar navbar-expand-lg rep-navbar py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            RealEstate<span style="color:var(--rep-accent)">Pro</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#repMobileNav" aria-controls="repMobileNav" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2" style="color:var(--rep-primary)"></i>
        </button>

        <!-- Desktop nav -->
        <div class="collapse navbar-collapse d-none d-lg-flex">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>

                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}" href="{{ route('properties.search') }}">Properties</a></li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
                @endauth

                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">How It Works</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <button type="button" class="rep-theme-toggle" id="repThemeToggle" aria-label="Toggle dark mode">
                    <i class="bi bi-moon-stars-fill" id="repThemeIcon" style="color:var(--rep-primary)"></i>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="rep-btn rep-btn-outline rep-btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="rep-btn rep-btn-primary rep-btn-sm">Register</a>
                @else
                    <div class="dropdown">
                        <button class="rep-btn rep-btn-outline rep-btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Str::limit(auth()->user()->name, 12) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rep-card p-2 mt-2">
                            @php
                                $dashboardRoute = match(auth()->user()->role) {
                                    'admin' => route('admin.dashboard'),
                                    'agent' => route('agent.dashboard'),
                                    default => route('user.dashboard'),
                                };
                            @endphp
                            <li><a class="dropdown-item rounded" href="{{ $dashboardRoute }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item rounded" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- Mobile offcanvas nav -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="repMobileNav">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title rep-h4">RealEstate<span style="color:var(--rep-accent)">Pro</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>

            @auth
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}" href="{{ route('properties.search') }}">Properties</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
            @endauth

            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">How It Works</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Contact</a></li>
        </ul>
        <div class="mt-auto d-flex flex-column gap-2">
            <button type="button" class="rep-theme-toggle align-self-start mb-2" id="repThemeToggleMobile" aria-label="Toggle dark mode">
                <i class="bi bi-moon-stars-fill" style="color:var(--rep-primary)"></i>
            </button>
            @guest
                <a href="{{ route('login') }}" class="rep-btn rep-btn-outline w-100">Login</a>
                <a href="{{ route('register') }}" class="rep-btn rep-btn-primary w-100">Register</a>
            @else
                <a href="{{ route('user.dashboard') }}" class="rep-btn rep-btn-primary w-100">Dashboard</a>
            @endguest
        </div>
    </div>
</div>
