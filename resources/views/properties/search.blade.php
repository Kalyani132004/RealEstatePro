{{--
    Property Search Results Page
    Route: GET /properties -> PropertyController@search (uses Query Builder Filters, Phase 15)
    Expected data:
      $properties -> paginated Collection<Property> (with appends(request()->query()))
      $categories, $locations, $amenities -> for filter sidebar
      Query params: keyword, category, location, listing_type, min_price, max_price,
                    bedrooms, bathrooms, amenities[], sort, featured
--}}
@extends('layouts.app')

@section('title', 'Search Properties')

@section('content')

    {{-- Page header --}}
    <section class="py-5" style="background: linear-gradient(135deg, var(--rep-primary), var(--rep-secondary));">
        <div class="container">
            <h1 class="rep-h2 text-white mb-1">Find Your Perfect Property</h1>
            <p class="mb-0" style="color: rgba(255,255,255,0.85);">Use the filters below to narrow down thousands of listings.</p>
        </div>
    </section>

    <section class="rep-section">
        <div class="container">
            <div class="row g-4">

                {{-- ============ FILTER SIDEBAR ============ --}}
                <div class="col-lg-3">
                    <button class="rep-btn rep-btn-outline w-100 mb-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">
                        <i class="bi bi-funnel"></i> Filters
                    </button>

                    <div class="collapse d-lg-block" id="filterPanel">
                        <form id="filterForm" method="GET" action="{{ route('properties.search') }}" class="rep-card p-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="rep-h4 mb-0">Advanced Filters</h3>
                                <a href="{{ route('properties.search') }}" class="rep-small">Reset</a>
                            </div>

                            <div class="mb-3">
                                <label class="form-label rep-small">Keyword</label>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control rep-input" placeholder="Title, address...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label rep-small">Listing Type</label>
                                <select name="listing_type" class="form-select rep-select">
                                    <option value="">Any</option>
                                    <option value="sale" {{ request('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                                    <option value="rent" {{ request('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label rep-small">Category</label>
                                <select name="category" class="form-select rep-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label rep-small">Location</label>
                                <select name="location" class="form-select rep-select">
                                    <option value="">All Locations</option>
                                    @foreach($locations ?? [] as $location)
                                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>{{ $location->city }}, {{ $location->state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label rep-small">Price Range (₹)</label>
                                <div class="d-flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control rep-input" placeholder="Min">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control rep-input" placeholder="Max">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label rep-small">Bedrooms</label>
                                    <select name="bedrooms" class="form-select rep-select">
                                        <option value="">Any</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label rep-small">Bathrooms</label>
                                    <select name="bathrooms" class="form-select rep-select">
                                        <option value="">Any</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label rep-small">Amenities</label>
                                <div style="max-height: 180px; overflow-y: auto;">
                                    @foreach($amenities ?? [] as $amenity)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="filterAmenity{{ $amenity->id }}"
                                                {{ in_array($amenity->id, (array) request('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label rep-small" for="filterAmenity{{ $amenity->id }}">{{ $amenity->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="rep-btn rep-btn-primary w-100"><i class="bi bi-funnel"></i> Apply Filters</button>
                        </form>
                    </div>
                </div>

                {{-- ============ RESULTS ============ --}}
                <div class="col-lg-9">
                    <div id="searchResults">
                        @include('properties.partials._results')
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/search-filters.js') }}"></script>
@endpush
