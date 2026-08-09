{{-- User / Buyer Dashboard Sidebar Navigation --}}

{{-- ==================== MENU ==================== --}}

<div class="rep-dash-nav-section-label">
    Menu
</div>

<a href="{{ route('user.dashboard') }}"
   class="rep-dash-nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
</a>

{{-- Properties --}}
<a href="{{ route('properties.search') }}"
   class="rep-dash-nav-link {{ request()->routeIs('properties.search') || request()->routeIs('properties.show') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i>
    <span>Properties</span>
</a>

{{-- Saved Properties --}}
<a href="{{ route('user.saved-properties') }}"
   class="rep-dash-nav-link {{ request()->routeIs('user.saved-properties') ? 'active' : '' }}">
    <i class="bi bi-heart"></i>
    <span>Saved Properties</span>
</a>

{{-- My Enquiries --}}
<a href="{{ route('user.enquiries') }}"
   class="rep-dash-nav-link {{ request()->routeIs('user.enquiries') ? 'active' : '' }}">
    <i class="bi bi-chat-square-text"></i>
    <span>My Enquiries</span>
</a>


{{-- ==================== ACCOUNT ==================== --}}

<div class="rep-dash-nav-section-label">
    Account
</div>

<a href="{{ route('user.profile') }}"
   class="rep-dash-nav-link {{ request()->routeIs('user.profile') || request()->routeIs('user.profile.update') ? 'active' : '' }}">
    <i class="bi bi-person"></i>
    <span>Profile</span>
</a>

<a href="{{ route('user.password') }}"
   class="rep-dash-nav-link {{ request()->routeIs('user.password') || request()->routeIs('user.password.update') ? 'active' : '' }}">
    <i class="bi bi-shield-lock"></i>
    <span>Change Password</span>
</a>

<a href="{{ route('home') }}"
   class="rep-dash-nav-link">
    <i class="bi bi-arrow-left-circle"></i>
    <span>Back to Site</span>
</a>