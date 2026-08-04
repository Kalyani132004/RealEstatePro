{{-- Admin Dashboard Sidebar Navigation --}}
<div class="rep-dash-nav-section-label">Overview</div>

<a href="{{ route('admin.dashboard') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('admin.reports') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Reports
</a>

<div class="rep-dash-nav-section-label">Management</div>

<a href="{{ route('admin.users.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Manage Users
</a>
<a href="{{ route('admin.agents.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
    <i class="bi bi-person-badge"></i> Manage Agents
</a>
<a href="{{ route('admin.properties.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
    <i class="bi bi-buildings"></i> Manage Properties
</a>
<a href="{{ route('admin.categories.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
    <i class="bi bi-tags"></i> Manage Categories
</a>
<a href="{{ route('admin.locations.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
    <i class="bi bi-geo-alt"></i> Manage Locations
</a>
<a href="{{ route('admin.enquiries.index') }}" class="rep-dash-nav-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
    <i class="bi bi-chat-square-text"></i> Manage Enquiries
</a>

<div class="rep-dash-nav-section-label">Account</div>

<a href="{{ route('user.profile') }}" class="rep-dash-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
    <i class="bi bi-person"></i> Profile
</a>
<a href="{{ route('home') }}" class="rep-dash-nav-link">
    <i class="bi bi-arrow-left-circle"></i> Back to Site
</a>
