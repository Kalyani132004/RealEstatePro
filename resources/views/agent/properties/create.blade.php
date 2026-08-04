{{--
    Agent Dashboard — Add Property
    Route: GET /agent/properties/create -> Agent\PropertyController@create
    Submits to: POST /agent/properties -> Agent\PropertyController@store (StorePropertyRequest, Phase 12/14/16/17)
    Expected data: $categories, $locations, $amenities
--}}
@extends('layouts.dashboard')

@section('page-title', 'Add Property')

@section('sidebar')
    <x-sidebar-agent />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Add New Property</h2>
        <p class="rep-small mb-0">Fill in the details below to publish a new listing.</p>
    </div>

    <form method="POST" action="{{ route('agent.properties.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        @include('agent.properties._form', ['property' => null])
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/agent.js') }}"></script>
    <script src="{{ asset('assets/js/video-upload.js') }}"></script>
@endpush
