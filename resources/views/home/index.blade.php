{{--
    Home Page
    Route: GET / -> HomeController@index
    Expected data from controller (wired in Phase 12):
      $categories          -> Illuminate\Support\Collection<Category> withCount('properties')
      $featuredProperties  -> Collection<Property> where is_featured = true, limit 6
      $recentProperties    -> Collection<Property> latest(), limit 8
      $stats               -> array [properties, agents, clients, cities]
      $locations            -> Collection<Location> for the search dropdown
--}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="rep-hero">
        <div class="container position-relative" style="z-index:2;">
            <div class="row">
                <div class="col-lg-8" data-aos="fade-up">
                    <span class="rep-badge" style="background: rgba(255,255,255,0.15); color:#fff;">
                        <i class="bi bi-patch-check-fill"></i> Trusted by 12,000+ home seekers
                    </span>
                    <h1 class="rep-h1 text-white mt-3 mb-3">Find a place you'll love to call home</h1>
                    <p class="rep-body mb-4" style="color: rgba(255,255,255,0.85); max-width: 560px;">
                        Browse verified listings, take immersive virtual tours, explore interactive floor plans,
                        and connect directly with agents you can trust.
                    </p>
                </div>
            </div>

            {{-- Glass search bar --}}
            <div class="rep-hero-search" data-aos="fade-up" data-aos-delay="150">
                <form action="{{ route('properties.search') }}" method="GET" class="rep-card-glass p-4">
                    <ul class="nav nav-pills mb-3 gap-2" id="listingTypeTabs">
                        <li class="nav-item">
                            <input type="radio" class="btn-check" name="listing_type" id="typeBuy" value="sale" checked>
                            <label class="rep-btn rep-btn-sm rep-btn-outline" for="typeBuy" style="color:#fff;border-color:rgba(255,255,255,0.4)">Buy</label>
                        </li>
                        <li class="nav-item">
                            <input type="radio" class="btn-check" name="listing_type" id="typeRent" value="rent">
                            <label class="rep-btn rep-btn-sm rep-btn-outline" for="typeRent" style="color:#fff;border-color:rgba(255,255,255,0.4)">Rent</label>
                        </li>
                    </ul>

                    <div class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="keyword" class="form-control rep-input" placeholder="Search by title, address, or landmark...">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select name="category" class="form-select rep-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select name="location" class="form-select rep-select">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->city }}, {{ $location->state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <button type="submit" class="rep-btn rep-btn-accent w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- ============ STATS ============ --}}
    <section class="py-5" style="background: var(--rep-surface); margin-top: 3rem;">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-lg-3" data-aos="fade-up">
                    <h2 class="rep-h2 mb-0 rep-counter" data-target="{{ $stats['properties'] ?? 0 }}">0</h2>
                    <p class="rep-small mb-0">Properties Listed</p>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="rep-h2 mb-0 rep-counter" data-target="{{ $stats['agents'] ?? 0 }}">0</h2>
                    <p class="rep-small mb-0">Verified Agents</p>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="rep-h2 mb-0 rep-counter" data-target="{{ $stats['clients'] ?? 0 }}">0</h2>
                    <p class="rep-small mb-0">Happy Clients</p>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="rep-h2 mb-0 rep-counter" data-target="{{ $stats['cities'] ?? 0 }}">0</h2>
                    <p class="rep-small mb-0">Cities Covered</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CATEGORIES ============ --}}
    <section class="rep-section" id="categories">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <p class="rep-small text-uppercase" style="color:var(--rep-secondary); letter-spacing:0.08em;">Browse</p>
                <h2 class="rep-h2">Explore by Category</h2>
            </div>
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <a href="{{ route('properties.search') }}?category={{ $category->slug }}" class="text-decoration-none">
                            <div class="rep-card text-center p-4 rep-hover-lift">
                                <i class="bi {{ $category->icon ?? 'bi-house-door' }} mb-3" style="font-size:2rem; color: var(--rep-secondary);"></i>
                                <h3 class="rep-h4 mb-1">{{ $category->name }}</h3>
                                <p class="rep-small mb-0">{{ $category->properties_count ?? 0 }} listings</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FEATURED PROPERTIES ============ --}}
    <section class="rep-section" style="background: var(--rep-surface);">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
                <div>
                    <p class="rep-small text-uppercase" style="color:var(--rep-secondary); letter-spacing:0.08em;">Handpicked</p>
                    <h2 class="rep-h2 mb-0">Featured Properties</h2>
                </div>
                <a href="{{ route('properties.search') }}?featured=1" class="rep-btn rep-btn-outline d-none d-md-inline-flex">View All</a>
            </div>
            <div class="row g-4">
                @forelse($featuredProperties as $property)
                    <div class="col-md-6 col-lg-4">
                        <x-property-card :property="$property" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="rep-text-muted">No featured properties yet. Check back soon.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section class="rep-section" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <p class="rep-small text-uppercase" style="color:var(--rep-secondary); letter-spacing:0.08em;">Simple Process</p>
                <h2 class="rep-h2">How RealEstatePro Works</h2>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="rep-card p-5 h-100">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px; background: rgba(var(--rep-primary-rgb),0.1);">
                            <i class="bi bi-search" style="font-size:1.6rem; color: var(--rep-primary);"></i>
                        </div>
                        <h3 class="rep-h4">1. Search &amp; Filter</h3>
                        <p class="rep-small">Use advanced filters to narrow down properties by price, location, category, and amenities.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="rep-card p-5 h-100">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px; background: rgba(var(--rep-secondary-rgb),0.1);">
                            <i class="bi bi-camera-reels" style="font-size:1.6rem; color: var(--rep-secondary);"></i>
                        </div>
                        <h3 class="rep-h4">2. Take a Virtual Tour</h3>
                        <p class="rep-small">Watch HTML5 video tours and explore interactive floor plans before you visit in person.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="rep-card p-5 h-100">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px; background: rgba(var(--rep-accent-rgb),0.15);">
                            <i class="bi bi-chat-dots" style="font-size:1.6rem; color: #9c6f0f;"></i>
                        </div>
                        <h3 class="rep-h4">3. Contact the Agent</h3>
                        <p class="rep-small">Send an enquiry directly to the listing agent and schedule your visit in minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ RECENT PROPERTIES ============ --}}
    <section class="rep-section" style="background: var(--rep-surface);">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
                <div>
                    <p class="rep-small text-uppercase" style="color:var(--rep-secondary); letter-spacing:0.08em;">Just Added</p>
                    <h2 class="rep-h2 mb-0">Recent Properties</h2>
                </div>
                <a href="{{ route('properties.search') }}?sort=latest" class="rep-btn rep-btn-outline d-none d-md-inline-flex">View All</a>
            </div>
            <div class="row g-4">
                @forelse($recentProperties as $property)
                    <div class="col-md-6 col-lg-3">
                        <x-property-card :property="$property" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="rep-text-muted">No properties available right now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ CTA BANNER ============ --}}
    <section class="rep-section">
        <div class="container">
            <div class="rep-card-glass p-5 text-center" style="background: linear-gradient(135deg, var(--rep-primary), var(--rep-secondary));" data-aos="zoom-in">
                <h2 class="rep-h2 text-white mb-3">Are you an agent or property owner?</h2>
                <p class="rep-body mb-4" style="color: rgba(255,255,255,0.85);">List your property on RealEstatePro and reach thousands of verified buyers and renters.</p>
                <a href="{{ route('register') }}" class="rep-btn rep-btn-accent rep-btn-lg">Get Started Free <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
    </script>
@endpush
