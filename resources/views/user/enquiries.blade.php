{{--
    User Dashboard — My Enquiries
    Route: GET /user/enquiries -> User\DashboardController@enquiries (or dedicated controller)
    Expected data: $enquiries -> paginated Collection<Enquiry> with property relation
--}}
@extends('layouts.dashboard')

@section('page-title', 'My Enquiries')

@section('sidebar')
    <x-sidebar-user />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">My Enquiries</h2>
        <p class="rep-small mb-0">Track the status of every enquiry you've sent to agents.</p>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        @forelse($enquiries ?? [] as $enquiry)
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 p-4 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--rep-border) !important;">
                <img src="{{ asset('storage/' . ($enquiry->property->cover_image ?? '')) }}" alt="" style="width:64px;height:64px;border-radius:var(--rep-radius-sm);object-fit:cover;flex-shrink:0;">

                <div class="flex-grow-1">
                    <a href="{{ route('properties.show', $enquiry->property->slug ?? '') }}" class="fw-semibold text-decoration-none rep-body" style="color: var(--rep-text);">
                        {{ $enquiry->property->title ?? 'Property no longer listed' }}
                    </a>
                    <p class="rep-small rep-text-muted mb-1">{{ Str::limit($enquiry->message, 90) }}</p>
                    <p class="rep-small mb-0"><i class="bi bi-calendar3 me-1"></i>{{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
                </div>

                <div class="text-md-end">
                    <span class="rep-badge rep-badge-{{ $enquiry->status === 'new' ? 'pending' : ($enquiry->status === 'closed' ? 'sold' : 'available') }} mb-2 d-inline-block">
                        {{ ucfirst($enquiry->status) }}
                    </span>
                    <br>
                    <a href="{{ route('properties.show', $enquiry->property->slug ?? '') }}" class="rep-small">View Property <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        @empty
            <div class="text-center p-5">
                <i class="bi bi-chat-square-text" style="font-size:2.5rem; color: var(--rep-text-muted);"></i>
                <h3 class="rep-h4 mt-3">No enquiries yet</h3>
                <p class="rep-small mb-3">When you contact an agent about a property, it'll show up here.</p>
                <a href="{{ route('properties.search') }}" class="rep-btn rep-btn-primary mx-auto" style="width:fit-content;">Browse Properties</a>
            </div>
        @endforelse
    </div>

    @if(isset($enquiries) && $enquiries->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $enquiries->links() }}
        </div>
    @endif

@endsection
