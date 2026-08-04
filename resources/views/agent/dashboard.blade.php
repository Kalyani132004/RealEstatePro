{{--
    Agent Dashboard — Overview
    Route: GET /agent/dashboard -> Agent\DashboardController@index
    Expected data (wired Phase 12):
      $totalProperties, $activeListings, $totalEnquiries, $totalViews
      $recentProperties -> Collection<Property> (latest 5, agent's own)
      $recentEnquiries  -> Collection<Enquiry> (latest 5, agent's own)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Agent Dashboard')

@section('sidebar')
    <x-sidebar-agent />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
            <p class="rep-small mb-0">Here's a snapshot of your listings and enquiries.</p>
        </div>
        <a href="{{ route('agent.properties.create') }}" class="rep-btn rep-btn-primary"><i class="bi bi-plus-circle"></i> Add New Property</a>
    </div>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(30,58,95,0.1); color: var(--rep-primary);"><i class="bi bi-buildings"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalProperties ?? 0 }}</h3><p class="rep-small mb-0">Total Properties</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(34,166,90,0.12); color: var(--rep-success);"><i class="bi bi-check-circle"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $activeListings ?? 0 }}</h3><p class="rep-small mb-0">Active Listings</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(14,165,160,0.12); color: var(--rep-secondary);"><i class="bi bi-chat-square-text"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalEnquiries ?? 0 }}</h3><p class="rep-small mb-0">Total Enquiries</p></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(212,168,83,0.15); color:#9c6f0f;"><i class="bi bi-eye"></i></div>
                <div><h3 class="rep-h3 mb-0">{{ $totalViews ?? 0 }}</h3><p class="rep-small mb-0">Total Views</p></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent properties --}}
        <div class="col-lg-7">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">My Recent Properties</h3>
                    <a href="{{ route('agent.properties.index') }}" class="rep-small">View All</a>
                </div>

                @forelse($recentProperties ?? [] as $property)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="" style="width:56px;height:56px;border-radius:var(--rep-radius-sm);object-fit:cover;">
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold rep-small">{{ Str::limit($property->title, 34) }}</p>
                            <p class="mb-0 rep-price rep-small">₹{{ number_format($property->price) }}</p>
                        </div>
                        <span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                        <a href="{{ route('agent.properties.edit', $property->id) }}" class="rep-btn rep-btn-outline rep-btn-sm"><i class="bi bi-pencil"></i></a>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">You haven't listed any properties yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent enquiries --}}
        <div class="col-lg-5">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">Recent Enquiries</h3>
                    <a href="{{ route('agent.enquiries') }}" class="rep-small">View All</a>
                </div>

                @forelse($recentEnquiries ?? [] as $enquiry)
                    <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <div class="rep-avatar-sm" style="background: rgba(var(--rep-secondary-rgb),0.15); color:var(--rep-secondary);">
                            {{ strtoupper(substr($enquiry->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold rep-small">{{ $enquiry->name }}</p>
                            <p class="mb-0 rep-small rep-text-muted">{{ Str::limit($enquiry->message, 50) }}</p>
                        </div>
                        <span class="rep-badge rep-badge-{{ $enquiry->status === 'new' ? 'pending' : ($enquiry->status === 'closed' ? 'sold' : 'available') }}">{{ ucfirst($enquiry->status) }}</span>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">No enquiries yet.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
