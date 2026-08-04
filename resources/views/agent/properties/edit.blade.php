{{--
    Agent Dashboard — Edit Property
    Route: GET /agent/properties/{property}/edit -> Agent\PropertyController@edit
    Submits to: PUT /agent/properties/{property} -> Agent\PropertyController@update (UpdatePropertyRequest, Phase 12/14)
    Expected data: $property, $categories, $locations, $amenities
--}}
@extends('layouts.dashboard')

@section('page-title', 'Edit Property')

@section('sidebar')
    <x-sidebar-agent />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Edit Property</h2>
        <p class="rep-small mb-0">Update the details for "{{ $property->title }}".</p>
    </div>

    <form method="POST" action="{{ route('agent.properties.update', $property->id) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        @include('agent.properties._form', ['property' => $property])
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/agent.js') }}"></script>
    <script src="{{ asset('assets/js/video-upload.js') }}"></script>
@endpush
