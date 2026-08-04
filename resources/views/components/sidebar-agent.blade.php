{{-- Agent Dashboard Sidebar Navigation --}}
<div class="rep-dash-nav-section-label">Menu</div>

<a href="{{ route('agent.dashboard') }}" class="rep-dash-nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('agent.properties.index') }}" class="rep-dash-nav-link {{ request()->routeIs('agent.properties.*') ? 'active' : '' }}">
    <i class="bi bi-buildings"></i> My Properties
</a>
<a href="{{ route('agent.properties.create') }}" class="rep-dash-nav-link {{ request()->routeIs('agent.properties.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle"></i> Add Property
</a>
<a href="{{ route('agent.enquiries') }}" class="rep-dash-nav-link {{ request()->routeIs('agent.enquiries') ? 'active' : '' }}">
    <i class="bi bi-chat-square-text"></i> Manage Enquiries
    @if(($newEnquiriesCount ?? 0) > 0)
        <span class="badge rounded-pill">{{ $newEnquiriesCount }}</span>
    @endif
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
