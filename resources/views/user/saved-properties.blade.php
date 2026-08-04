{{--
    User Dashboard — Saved Properties
    Route: GET /user/saved-properties -> User\SavedPropertyController@index
    Expected data: $savedProperties -> paginated Collection<Property>
--}}
@extends('layouts.dashboard')

@section('page-title', 'Saved Properties')

@section('sidebar')
    <x-sidebar-user />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Saved Properties</h2>
            <p class="rep-small mb-0">Properties you've bookmarked for later.</p>
        </div>
        <a href="{{ route('properties.search') }}" class="rep-btn rep-btn-outline"><i class="bi bi-search"></i> Browse More</a>
    </div>

    <div class="row g-4">
        @forelse($savedProperties ?? [] as $property)
            <div class="col-md-6 col-xl-4">
                <x-property-card :property="$property" />
            </div>
        @empty
            <div class="col-12">
                <div class="rep-card text-center p-5">
                    <i class="bi bi-heart" style="font-size:2.5rem; color: var(--rep-text-muted);"></i>
                    <h3 class="rep-h4 mt-3">No saved properties yet</h3>
                    <p class="rep-small mb-3">Tap the heart icon on any listing to save it here for quick access.</p>
                    <a href="{{ route('properties.search') }}" class="rep-btn rep-btn-primary mx-auto" style="width:fit-content;">Browse Properties</a>
                </div>
            </div>
        @endforelse
    </div>

    @if(isset($savedProperties) && $savedProperties->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $savedProperties->links() }}
        </div>
    @endif

@endsection
