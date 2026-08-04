{{--
    User Dashboard — Overview
    Route: GET /user/dashboard -> User\DashboardController@index
    Expected data (wired Phase 12):
      $savedCount, $enquiriesCount, $profileCompletion (int %)
      $recentEnquiries -> Collection<Enquiry> (latest 5, with property relation)
      $recentSaved     -> Collection<Property> (latest 4 saved)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('sidebar')
    <x-sidebar-user />
@endsection

@section('content')

    <h2 class="rep-h3 mb-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
    <p class="rep-small mb-4">Here's what's happening with your account today.</p>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(229,72,77,0.12); color: var(--rep-danger);">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div>
                    <h3 class="rep-h3 mb-0">{{ $savedCount ?? 0 }}</h3>
                    <p class="rep-small mb-0">Saved Properties</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(14,165,160,0.12); color: var(--rep-secondary);">
                    <i class="bi bi-chat-square-text-fill"></i>
                </div>
                <div>
                    <h3 class="rep-h3 mb-0">{{ $enquiriesCount ?? 0 }}</h3>
                    <p class="rep-small mb-0">Enquiries Sent</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="rep-stat-card rep-dash-stat">
                <div class="rep-dash-stat-icon" style="background: rgba(212,168,83,0.15); color:#9c6f0f;">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="rep-h3 mb-0">{{ $profileCompletion ?? 60 }}%</h3>
                    <p class="rep-small mb-1">Profile Completion</p>
                    <div class="progress" style="height:6px; border-radius: 999px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $profileCompletion ?? 60 }}%; background: var(--rep-secondary);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent enquiries --}}
        <div class="col-lg-7">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">Recent Enquiries</h3>
                    <a href="{{ route('user.enquiries') }}" class="rep-small">View All</a>
                </div>

                @forelse($recentEnquiries ?? [] as $enquiry)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <div class="rep-avatar-sm" style="background: rgba(var(--rep-secondary-rgb),0.15); color:var(--rep-secondary);">
                            <i class="bi bi-house"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold rep-small">{{ $enquiry->property->title ?? 'Property' }}</p>
                            <p class="mb-0 rep-small rep-text-muted">{{ Str::limit($enquiry->message, 60) }}</p>
                        </div>
                        <span class="rep-badge rep-badge-{{ $enquiry->status === 'new' ? 'pending' : ($enquiry->status === 'closed' ? 'sold' : 'available') }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">You haven't sent any enquiries yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recently saved properties --}}
        <div class="col-lg-5">
            <div class="rep-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="rep-h4 mb-0">Recently Saved</h3>
                    <a href="{{ route('user.saved-properties') }}" class="rep-small">View All</a>
                </div>

                @forelse($recentSaved ?? [] as $property)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="{{ $property->title }}" style="width:56px;height:56px;border-radius:var(--rep-radius-sm);object-fit:cover;">
                        <div class="flex-grow-1">
                            <a href="{{ route('properties.show', $property->slug) }}" class="mb-0 fw-semibold rep-small text-decoration-none" style="color: var(--rep-text);">
                                {{ Str::limit($property->title, 28) }}
                            </a>
                            <p class="mb-0 rep-price rep-small">₹{{ number_format($property->price) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="rep-text-muted text-center py-4 mb-0">No saved properties yet. Start exploring!</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
