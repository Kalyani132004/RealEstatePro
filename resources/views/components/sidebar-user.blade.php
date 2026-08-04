{{-- User Dashboard Sidebar Navigation --}}
<div class="rep-dash-nav-section-label">Menu</div>

<a href="{{ route('user.dashboard') }}" class="rep-dash-nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('user.saved-properties') }}" class="rep-dash-nav-link {{ request()->routeIs('user.saved-properties') ? 'active' : '' }}">
    <i class="bi bi-heart"></i> Saved Properties
</a>
<a href="{{ route('user.enquiries') }}" class="rep-dash-nav-link {{ request()->routeIs('user.enquiries') ? 'active' : '' }}">
    <i class="bi bi-chat-square-text"></i> My Enquiries
</a>

<div class="rep-dash-nav-section-label">Account</div>

<a href="{{ route('user.profile') }}" class="rep-dash-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
    <i class="bi bi-person"></i> Profile
</a>
<a href="{{ route('user.password') }}" class="rep-dash-nav-link {{ request()->routeIs('user.password') ? 'active' : '' }}">
    <i class="bi bi-shield-lock"></i> Change Password
</a>
<a href="{{ route('home') }}" class="rep-dash-nav-link">
    <i class="bi bi-arrow-left-circle"></i> Back to Site
</a>
