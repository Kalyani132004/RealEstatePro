{{--
    Property Search Results Page
    Route: GET /properties
    Controller: PropertyController@search

    Expected data:
    $properties
    $categories
    $locations
    $amenities
--}}

@extends('layouts.app')

@section('title', 'Search Properties')

@section('content')

{{-- =========================================================
     PAGE HEADER
========================================================= --}}
<section class="py-5"
    style="background: linear-gradient(135deg, var(--rep-primary), var(--rep-secondary));">

    <div class="container">

        <h1 class="rep-h2 text-white mb-1">
            Find Your Perfect Property
        </h1>

        <p class="mb-0"
            style="color: rgba(255,255,255,0.85);">
            Use the filters below to narrow down thousands of listings.
        </p>

    </div>
</section>


{{-- =========================================================
     SEARCH SECTION
========================================================= --}}
<section class="rep-section">

    <div class="container">

        <div class="row g-4">


            {{-- =====================================================
                 FILTER SIDEBAR
            ====================================================== --}}
            <div class="col-lg-3">

                {{-- Mobile Filter Button --}}
                <button
                    class="rep-btn rep-btn-outline w-100 mb-3 d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filterPanel">

                    <i class="bi bi-funnel"></i>
                    Filters

                </button>


                <div class="collapse d-lg-block" id="filterPanel">

                    <form
                        id="filterForm"
                        method="GET"
                        action="{{ route('properties.search') }}"
                        class="rep-card p-4">

                        {{-- Filter Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h3 class="rep-h4 mb-0">
                                Advanced Filters
                            </h3>

                            <a
                                href="{{ route('properties.search') }}"
                                class="rep-small">

                                Reset

                            </a>

                        </div>


                        {{-- =================================================
                             KEYWORD
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label rep-small">
                                Keyword
                            </label>

                            <input
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                class="form-control rep-input"
                                placeholder="Title, address...">

                        </div>


                        {{-- =================================================
                             LISTING TYPE
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label rep-small">
                                Listing Type
                            </label>

                            <select
                                name="listing_type"
                                class="form-select rep-select">

                                <option value="">
                                    Any
                                </option>

                                <option
                                    value="sale"
                                    {{ request('listing_type') === 'sale' ? 'selected' : '' }}>
                                    For Sale
                                </option>

                                <option
                                    value="rent"
                                    {{ request('listing_type') === 'rent' ? 'selected' : '' }}>
                                    For Rent
                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             CATEGORY
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label rep-small">
                                Category
                            </label>

                            <select
                                name="category"
                                class="form-select rep-select">

                                <option value="">
                                    All Categories
                                </option>

                                @foreach($categories ?? [] as $category)

                                    <option
                                        value="{{ $category->slug }}"
                                        {{ request('category') === $category->slug ? 'selected' : '' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             LOCATION
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label rep-small">
                                Location
                            </label>

                            <select
                                name="location"
                                class="form-select rep-select">

                                <option value="">
                                    All Locations
                                </option>

                                @foreach($locations ?? [] as $location)

                                    <option
                                        value="{{ $location->id }}"
                                        {{ request('location') == $location->id ? 'selected' : '' }}>

                                        {{ $location->city }}, {{ $location->state }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             PRICE RANGE
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label rep-small">
                                Price Range (₹)
                            </label>

                            <div class="d-flex gap-2">

                                <input
                                    type="number"
                                    name="min_price"
                                    value="{{ request('min_price') }}"
                                    class="form-control rep-input"
                                    placeholder="Min">

                                <input
                                    type="number"
                                    name="max_price"
                                    value="{{ request('max_price') }}"
                                    class="form-control rep-input"
                                    placeholder="Max">

                            </div>

                        </div>


                        {{-- =================================================
                             BEDROOMS / BATHROOMS
                        ================================================== --}}
                        <div class="row g-2 mb-3">

                            <div class="col-6">

                                <label class="form-label rep-small">
                                    Bedrooms
                                </label>

                                <select
                                    name="bedrooms"
                                    class="form-select rep-select">

                                    <option value="">
                                        Any
                                    </option>

                                    @for($i = 1; $i <= 5; $i++)

                                        <option
                                            value="{{ $i }}"
                                            {{ request('bedrooms') == $i ? 'selected' : '' }}>

                                            {{ $i }}+

                                        </option>

                                    @endfor

                                </select>

                            </div>


                            <div class="col-6">

                                <label class="form-label rep-small">
                                    Bathrooms
                                </label>

                                <select
                                    name="bathrooms"
                                    class="form-select rep-select">

                                    <option value="">
                                        Any
                                    </option>

                                    @for($i = 1; $i <= 5; $i++)

                                        <option
                                            value="{{ $i }}"
                                            {{ request('bathrooms') == $i ? 'selected' : '' }}>

                                            {{ $i }}+

                                        </option>

                                    @endfor

                                </select>

                            </div>

                        </div>


                        {{-- =================================================
                             AMENITIES
                        ================================================== --}}
                        <div class="mb-4">

                            <label class="form-label rep-small">
                                Amenities
                            </label>

                            <div
                                style="max-height:180px; overflow-y:auto;">

                                @foreach($amenities ?? [] as $amenity)

                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="amenities[]"
                                            value="{{ $amenity->id }}"
                                            id="filterAmenity{{ $amenity->id }}"
                                            {{ in_array($amenity->id, (array) request('amenities', [])) ? 'checked' : '' }}>

                                        <label
                                            class="form-check-label rep-small"
                                            for="filterAmenity{{ $amenity->id }}">

                                            {{ $amenity->name }}

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- APPLY FILTER BUTTON --}}
                        <button
                            type="submit"
                            class="rep-btn rep-btn-primary w-100">

                            <i class="bi bi-funnel"></i>
                            Apply Filters

                        </button>

                    </form>

                </div>

            </div>


            {{-- =========================================================
                 PROPERTY RESULTS
            ========================================================== --}}
            <div class="col-lg-9">


                {{-- Results Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <p class="rep-small mb-0">

                            Showing

                            <strong>
                                {{ $properties->count() }}
                            </strong>

                            of

                            <strong>
                                {{ $properties->total() }}
                            </strong>

                            properties

                        </p>

                    </div>


                    {{-- Sort --}}
                    <form
                        method="GET"
                        action="{{ route('properties.search') }}">

                        @foreach(request()->except('sort', 'page') as $key => $value)

                            @if(is_array($value))

                                @foreach($value as $item)

                                    <input
                                        type="hidden"
                                        name="{{ $key }}[]"
                                        value="{{ $item }}">

                                @endforeach

                            @else

                                <input
                                    type="hidden"
                                    name="{{ $key }}"
                                    value="{{ $value }}">

                            @endif

                        @endforeach


                        <select
                            name="sort"
                            class="form-select"
                            onchange="this.form.submit()">

                            <option
                                value="newest"
                                {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>

                                Newest First

                            </option>

                            <option
                                value="oldest"
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>

                                Oldest First

                            </option>

                            <option
                                value="price_low"
                                {{ request('sort') === 'price_low' ? 'selected' : '' }}>

                                Price: Low to High

                            </option>

                            <option
                                value="price_high"
                                {{ request('sort') === 'price_high' ? 'selected' : '' }}>

                                Price: High to Low

                            </option>

                        </select>

                    </form>

                </div>


                {{-- =====================================================
                     PROPERTY CARDS
                ====================================================== --}}

                @if($properties->count() > 0)

                    <div class="row g-4">

                        @foreach($properties as $property)

                            <div class="col-md-6">

                                <div
                                    class="rep-card h-100 overflow-hidden"
                                    style="transition:transform .2s ease, box-shadow .2s ease;">

                                    {{-- PROPERTY IMAGE --}}
                                    <div
                                        style="
                                            position:relative;
                                            height:230px;
                                            overflow:hidden;
                                            background:#f1f5f9;
                                        ">

                                        @if($property->cover_image)

                                            <img
                                                src="{{ asset('storage/' . $property->cover_image) }}"
                                                alt="{{ $property->title }}"
                                                style="
                                                    width:100%;
                                                    height:100%;
                                                    object-fit:cover;
                                                ">

                                        @else

                                            <div
                                                class="d-flex align-items-center justify-content-center h-100">

                                                <i
                                                    class="bi bi-house-door"
                                                    style="font-size:50px;color:#94a3b8;">
                                                </i>

                                            </div>

                                        @endif


                                        {{-- Featured Badge --}}
                                        @if($property->featured ?? false)

                                            <span
                                                style="
                                                    position:absolute;
                                                    top:15px;
                                                    left:15px;
                                                    background:var(--rep-accent);
                                                    color:#fff;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-size:12px;
                                                    font-weight:600;
                                                ">

                                                <i class="bi bi-star-fill"></i>
                                                Featured

                                            </span>

                                        @endif


                                        {{-- Listing Type --}}
                                        <span
                                            style="
                                                position:absolute;
                                                top:15px;
                                                right:15px;
                                                background:rgba(0,0,0,.65);
                                                color:#fff;
                                                padding:6px 12px;
                                                border-radius:20px;
                                                font-size:12px;
                                                font-weight:600;
                                            ">

                                            {{ ucfirst($property->listing_type ?? 'Sale') }}

                                        </span>

                                    </div>


                                    {{-- PROPERTY DETAILS --}}
                                    <div class="p-4">

                                        {{-- Price --}}
                                        <div class="mb-2">

                                            <span
                                                class="rep-price"
                                                style="font-size:22px;font-weight:700;">

                                                ₹{{ number_format($property->price ?? 0) }}

                                            </span>

                                            @if(($property->listing_type ?? '') === 'rent')

                                                <span class="rep-small rep-text-muted">
                                                    / month
                                                </span>

                                            @endif

                                        </div>


                                        {{-- Title --}}
                                        <h3
                                            class="rep-h4 mb-2"
                                            style="
                                                display:-webkit-box;
                                                -webkit-line-clamp:2;
                                                -webkit-box-orient:vertical;
                                                overflow:hidden;
                                            ">

                                            {{ $property->title }}

                                        </h3>


                                        {{-- Location --}}
                                        <p class="rep-small rep-text-muted mb-3">

                                            <i class="bi bi-geo-alt"></i>

                                            @if($property->location)

                                                {{ $property->location->city ?? '' }}

                                                @if($property->location->state)
                                                    , {{ $property->location->state }}
                                                @endif

                                            @elseif(isset($property->address))

                                                {{ $property->address }}

                                            @else

                                                Location not available

                                            @endif

                                        </p>


                                        {{-- Property Features --}}
                                        <div
                                            class="d-flex flex-wrap gap-3 mb-3">

                                            @if(isset($property->bedrooms))

                                                <span class="rep-small">

                                                    <i class="bi bi-door-open"></i>

                                                    {{ $property->bedrooms }}
                                                    Beds

                                                </span>

                                            @endif


                                            @if(isset($property->bathrooms))

                                                <span class="rep-small">

                                                    <i class="bi bi-droplet"></i>

                                                    {{ $property->bathrooms }}
                                                    Baths

                                                </span>

                                            @endif


                                            @if(isset($property->area))

                                                <span class="rep-small">

                                                    <i class="bi bi-bounding-box"></i>

                                                    {{ $property->area }}
                                                    sq.ft

                                                </span>

                                            @endif

                                        </div>


                                        {{-- View Property --}}
                                        <a
                                            href="{{ route('properties.show', $property->slug) }}"
                                            class="rep-btn rep-btn-primary w-100">

                                            <i class="bi bi-eye"></i>

                                            View Property

                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- Pagination --}}
                    @if($properties->hasPages())

                        <div class="d-flex justify-content-center mt-5">

                            {{ $properties->appends(request()->query())->links() }}

                        </div>

                    @endif


                @else

                    {{-- =================================================
                         NO RESULTS
                    ================================================== --}}

                    <div class="rep-card p-5 text-center">

                        <div class="mb-3">

                            <i
                                class="bi bi-search"
                                style="
                                    font-size:55px;
                                    color:var(--rep-text-muted);
                                ">
                            </i>

                        </div>

                        <h3 class="rep-h4 mb-2">
                            No properties match your filters
                        </h3>

                        <p class="rep-text-muted mb-4">
                            Try adjusting or clearing some filters.
                        </p>

                        <a
                            href="{{ route('properties.search') }}"
                            class="rep-btn rep-btn-primary">

                            Clear Filters

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection


{{-- =========================================================
     SCRIPTS
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
     * Prevent invalid price range
     */
    const filterForm = document.getElementById('filterForm');

    if (filterForm) {

        filterForm.addEventListener('submit', function (event) {

            const minPrice =
                parseFloat(
                    document.querySelector('[name="min_price"]')?.value || 0
                );

            const maxPrice =
                parseFloat(
                    document.querySelector('[name="max_price"]')?.value || 0
                );

            if (minPrice > 0 && maxPrice > 0 && minPrice > maxPrice) {

                event.preventDefault();

                alert('Minimum price cannot be greater than maximum price.');

            }

        });

    }

});

</script>

@endpush