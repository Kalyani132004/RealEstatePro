{{--
    Admin Dashboard — Overview
    Route: GET /admin/dashboard -> Admin\DashboardController@index
    Expected data (wired Phase 12):
      $totalUsers, $totalAgents, $totalProperties, $totalEnquiries
      $recentUsers -> Collection<User> (latest 5)
      $recentProperties -> Collection<Property> (latest 5, with agent)
      $propertiesByCategory -> array ['labels' => [...], 'data' => [...]] for chart
      $enquiriesTrend -> array ['labels' => [...], 'data' => [...]] for chart (last 7 days)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Platform Overview</h2>
        <p class="rep-small mb-0">A bird's-eye view of everything happening on RealEstatePro.</p>
    </div>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(30,58,95,0.1); color: var(--rep-primary);"><i class="bi bi-people"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalUsers ?? 0 }}</h3><p class="rep-small mb-0">Total Users</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(14,165,160,0.12); color: var(--rep-secondary);"><i class="bi bi-person-badge"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalAgents ?? 0 }}</h3><p class="rep-small mb-0">Verified Agents</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(212,168,83,0.15); color:#9c6f0f;"><i class="bi bi-buildings"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalProperties ?? 0 }}</h3><p class="rep-small mb-0">Total Properties</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(34,166,90,0.12); color: var(--rep-success);"><i class="bi bi-chat-square-text"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalEnquiries ?? 0 }}</h3><p class="rep-small mb-0">Total Enquiries</p></div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="rep-card p-4 h-100">
                <h3 class="rep-h4 mb-3">Properties by Category</h3>
                <canvas id="categoryChart" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rep-card p-4 h-100">
                <h3 class="rep-h4 mb-3">Enquiries — Last 7 Days</h3>
                <canvas id="enquiriesChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent users --}}
        <div class="col-lg-6">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">Recently Registered</h3>
                    <a href="{{ route('admin.users.index') }}" class="rep-small">View All</a>
                </div>
                @forelse($recentUsers ?? [] as $user)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <div class="rep-avatar-sm" style="background: rgba(var(--rep-secondary-rgb),0.15); color:var(--rep-secondary);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold rep-small">{{ $user->name }}</p>
                            <p class="mb-0 rep-small rep-text-muted">{{ $user->email }}</p>
                        </div>
                        <span class="rep-badge rep-badge-available">{{ ucfirst($user->role) }}</span>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">No users yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent properties --}}
        <div class="col-lg-6">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">Recently Listed</h3>
                    <a href="{{ route('admin.properties.index') }}" class="rep-small">View All</a>
                </div>
                @forelse($recentProperties ?? [] as $property)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="" style="width:48px;height:48px;border-radius:var(--rep-radius-sm);object-fit:cover;">
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold rep-small">{{ Str::limit($property->title, 30) }}</p>
                            <p class="mb-0 rep-small rep-text-muted">by {{ $property->agent->user->name ?? 'Unknown' }}</p>
                        </div>
                        <span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">No properties yet.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        window.repChartData = {
            categoryLabels: @json($propertiesByCategory['labels'] ?? []),
            categoryData: @json($propertiesByCategory['data'] ?? []),
            enquiryLabels: @json($enquiriesTrend['labels'] ?? []),
            enquiryData: @json($enquiriesTrend['data'] ?? []),
        };
    </script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
