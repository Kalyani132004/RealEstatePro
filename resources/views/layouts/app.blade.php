<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'RealEstatePro — Discover, tour, and enquire about premium properties for sale and rent.')">

    <title>@yield('title', 'RealEstatePro') | Property Listing &amp; Virtual Tour Portal</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- AOS (scroll animations) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- RealEstatePro Design System -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    <!-- Inline: apply saved theme BEFORE first paint to avoid flash-of-wrong-theme -->
    <script>
        (function () {
            var savedTheme = localStorage.getItem('rep-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    @stack('styles')
</head>
<body>

    <!-- Full page loader -->
    <div class="rep-page-loader" id="repPageLoader">
        <div class="rep-loader-logo">RealEstate<span style="color:var(--rep-accent)">Pro</span></div>
    </div>

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Toast container (populated by app.js / session flash) -->
    <div class="rep-toast-container" id="repToastContainer"></div>

    <!-- Page content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Session flash -> toast bridge -->
    @if (session('success'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('success')), 'success'); });</script>
    @endif
    @if (session('error'))
        <script>window.addEventListener('DOMContentLoaded', function () { repToast(@json(session('error')), 'danger'); });</script>
    @endif
    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                repToast(@json($errors->first()), 'danger');
            });
        </script>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
