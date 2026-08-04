<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome') | RealEstatePro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    <script>
        (function () {
            var savedTheme = localStorage.getItem('rep-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>

    <div class="rep-page-loader" id="repPageLoader">
        <div class="rep-loader-logo">RealEstate<span style="color:var(--rep-accent)">Pro</span></div>
    </div>

    <div class="rep-toast-container" id="repToastContainer"></div>

    <div class="d-flex min-vh-100">

        {{-- Brand panel — hidden on mobile --}}
        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 position-relative"
             style="background: linear-gradient(155deg, var(--rep-primary), var(--rep-secondary)); color: #fff;">
            <div>
                <a href="{{ route('home') }}" class="rep-h3 text-white text-decoration-none">
                    RealEstate<span style="color:var(--rep-accent)">Pro</span>
                </a>
            </div>

            <div>
                <h2 class="rep-h2 text-white mb-3">Your next property is one search away.</h2>
                <p style="color: rgba(255,255,255,0.85);">
                    Browse verified listings, tour homes virtually, and talk directly to trusted agents —
                    all in one modern platform built for how people actually house-hunt today.
                </p>
                <div class="d-flex gap-4 mt-4">
                    <div>
                        <h3 class="rep-h3 text-white mb-0">12K+</h3>
                        <p class="rep-small" style="color: rgba(255,255,255,0.7)">Listings</p>
                    </div>
                    <div>
                        <h3 class="rep-h3 text-white mb-0">3.4K+</h3>
                        <p class="rep-small" style="color: rgba(255,255,255,0.7)">Verified Agents</p>
                    </div>
                    <div>
                        <h3 class="rep-h3 text-white mb-0">98%</h3>
                        <p class="rep-small" style="color: rgba(255,255,255,0.7)">Satisfaction</p>
                    </div>
                </div>
            </div>

            <p class="rep-small mb-0" style="color: rgba(255,255,255,0.6)">&copy; {{ date('Y') }} RealEstatePro</p>
        </div>

        {{-- Form panel --}}
        <div class="col-lg-7 col-12 d-flex align-items-center justify-content-center py-5 px-3" style="background: var(--rep-bg);">
            <div class="w-100" style="max-width: 460px;">

                <div class="d-flex d-lg-none justify-content-between align-items-center mb-4">
                    <a href="{{ route('home') }}" class="rep-h4 text-decoration-none" style="color: var(--rep-text);">
                        RealEstate<span style="color:var(--rep-accent)">Pro</span>
                    </a>
                    <button type="button" class="rep-theme-toggle" id="repThemeToggle" aria-label="Toggle dark mode">
                        <i class="bi bi-moon-stars-fill" id="repThemeIcon" style="color:var(--rep-primary)"></i>
                    </button>
                </div>

                <div class="d-none d-lg-flex justify-content-end mb-3">
                    <button type="button" class="rep-theme-toggle" id="repThemeToggleDesktop" aria-label="Toggle dark mode">
                        <i class="bi bi-moon-stars-fill" style="color:var(--rep-primary)"></i>
                    </button>
                </div>

                @yield('content')

            </div>
        </div>
    </div>

    @if (session('success'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('success')), 'success'); });</script>
    @endif
    @if (session('status'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('status')), 'success'); });</script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>
