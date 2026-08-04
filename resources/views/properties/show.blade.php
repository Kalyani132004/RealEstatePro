{{--
    Property Details Page
    Route: GET /properties/{property:slug} -> PropertyController@show
    Expected data:
      $property -> Property model with: category, location, agent.user, galleries, amenities
      $relatedProperties -> Collection<Property> (same category, limit 3)
    Note: the "Nearby Locations" section below reads an optional $property->nearby_places
    array (e.g. [['name'=>'Green Valley School','distance'=>'0.8 km','icon'=>'bi-mortarboard']]).
    If that column isn't added to the properties table, simply omit the section or pass an
    empty array — it degrades gracefully.
--}}
@extends('layouts.app')

@section('title', $property->title)
@section('meta_description', Str::limit(strip_tags($property->description), 155))

@section('content')

    {{-- Breadcrumb --}}
    <div class="container pt-4">
        <nav class="rep-small mb-0">
            <a href="{{ route('home') }}" class="text-decoration-none">Home</a> /
            <a href="{{ route('properties.search') }}" class="text-decoration-none">Properties</a> /
            <span class="rep-text-muted">{{ Str::limit($property->title, 40) }}</span>
        </nav>
    </div>

    <section class="rep-section pt-3">
        <div class="container">

            {{-- ============ TITLE BAR ============ --}}
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <div class="d-flex gap-2 mb-2">
                        <span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                        @if($property->is_featured)
                            <span class="rep-badge rep-badge-featured"><i class="bi bi-star-fill"></i> Featured</span>
                        @endif
                    </div>
                    <h1 class="rep-h2 mb-1">{{ $property->title }}</h1>
                    <p class="rep-small mb-0"><i class="bi bi-geo-alt"></i> {{ $property->address }}, {{ $property->location->city ?? '' }}, {{ $property->location->state ?? '' }}</p>
                </div>
                <div class="text-lg-end">
                    <h2 class="rep-h2 rep-price mb-1">₹{{ number_format($property->price) }}{{ $property->listing_type === 'rent' ? '/mo' : '' }}</h2>
                    <button type="button" class="rep-btn rep-btn-outline rep-wishlist-btn" data-property-id="{{ $property->id }}">
                        <i class="bi {{ auth()->check() && auth()->user()->savedProperties->contains($property->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                        Save
                    </button>
                </div>
            </div>

            {{-- ============ IMAGE GALLERY ============ --}}
            <div class="rep-card p-2 mb-4" data-aos="fade-up">
                <div class="row g-2">
                    <div class="col-md-8">
                        <a href="{{ asset('storage/' . $property->cover_image) }}" class="rep-gallery-trigger" data-gallery-index="0">
                            <img
                                src="{{ asset('storage/' . $property->cover_image) }}"
                                srcset="{{ asset('storage/' . $property->cover_image) }} 1200w, {{ asset('storage/' . $property->cover_image) }} 600w"
                                sizes="(max-width: 768px) 100vw, 66vw"
                                alt="{{ $property->title }}"
                                style="width:100%; height: 420px; object-fit: cover; border-radius: var(--rep-radius-md); cursor: zoom-in;"
                            >
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="row g-2 h-100">
                            @forelse($property->galleries->take(4) as $index => $image)
                                <div class="col-6" style="height: 205px;">
                                    <a href="{{ asset('storage/' . $image->image_path) }}" class="rep-gallery-trigger" data-gallery-index="{{ $index + 1 }}">
                                        <img src="{{ $image->thumbnail_url }}" alt="Gallery image {{ $index + 1 }}"
                                             loading="lazy"
                                             style="width:100%; height:100%; object-fit:cover; border-radius: var(--rep-radius-sm); cursor: zoom-in;">
                                    </a>
                                </div>
                            @empty
                                <div class="col-12 d-flex align-items-center justify-content-center h-100 rep-text-muted rep-small">
                                    No additional gallery images yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lightbox modal --}}
            <div class="modal fade" id="galleryLightbox" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0">
                        <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="position-relative">
                            <img id="lightboxImage" src="" alt="Property image" style="width:100%; max-height:80vh; object-fit:contain; border-radius: var(--rep-radius-md);">
                            <button type="button" id="lightboxPrev" class="rep-theme-toggle position-absolute top-50 start-0 translate-middle-y ms-2" style="background: rgba(255,255,255,0.85);"><i class="bi bi-chevron-left"></i></button>
                            <button type="button" id="lightboxNext" class="rep-theme-toggle position-absolute top-50 end-0 translate-middle-y me-2" style="background: rgba(255,255,255,0.85);"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">

                    {{-- ============ FEATURES ============ --}}
                    <div class="rep-card p-4 mb-4" data-aos="fade-up">
                        <h3 class="rep-h4 mb-3">Property Features</h3>
                        <div class="row g-3 text-center">
                            <div class="col-4 col-md-2">
                                <i class="bi bi-door-closed d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ $property->bedrooms }}</p>
                                <p class="rep-small mb-0">Bedrooms</p>
                            </div>
                            <div class="col-4 col-md-2">
                                <i class="bi bi-droplet d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ $property->bathrooms }}</p>
                                <p class="rep-small mb-0">Bathrooms</p>
                            </div>
                            <div class="col-4 col-md-2">
                                <i class="bi bi-arrows-angle-expand d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ number_format($property->area_sqft) }}</p>
                                <p class="rep-small mb-0">Sqft</p>
                            </div>
                            <div class="col-4 col-md-2">
                                <i class="bi bi-building d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ $property->floors }}</p>
                                <p class="rep-small mb-0">Floors</p>
                            </div>
                            <div class="col-4 col-md-2">
                                <i class="bi bi-calendar3 d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ $property->year_built ?? '—' }}</p>
                                <p class="rep-small mb-0">Built</p>
                            </div>
                            <div class="col-4 col-md-2">
                                <i class="bi bi-tag d-block mb-1" style="font-size:1.4rem; color: var(--rep-secondary);"></i>
                                <p class="rep-small mb-0 fw-semibold">{{ ucfirst($property->listing_type) }}</p>
                                <p class="rep-small mb-0">Listing</p>
                            </div>
                        </div>
                    </div>

                    {{-- ============ DESCRIPTION ============ --}}
                    <div class="rep-card p-4 mb-4" data-aos="fade-up">
                        <h3 class="rep-h4 mb-3">Description</h3>
                        <p class="rep-body" style="white-space: pre-line;">{{ $property->description }}</p>
                    </div>

                    {{-- ============ VIRTUAL TOUR VIDEO ============ --}}
                    @if($property->virtual_tour_video)
                        <div class="rep-card p-4 mb-4" data-aos="fade-up">
                            <h3 class="rep-h4 mb-3"><i class="bi bi-camera-reels me-2"></i>Virtual Tour</h3>
                            <video
                                id="virtualTourVideo"
                                controls
                                preload="metadata"
                                poster="{{ asset('storage/' . $property->cover_image) }}"
                                style="width:100%; border-radius: var(--rep-radius-md); background:#000;"
                            >
                                <source src="{{ asset('storage/' . $property->virtual_tour_video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif

                    {{-- ============ INTERACTIVE FLOOR PLAN (CANVAS) ============ --}}
                    @if($property->floor_plan_image)
                        <div class="rep-card p-4 mb-4" data-aos="fade-up">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="rep-h4 mb-0"><i class="bi bi-bounding-box me-2"></i>Interactive Floor Plan</h3>
                                <div class="d-flex gap-2">
                                    <button type="button" id="floorPlanZoomIn" class="rep-btn rep-btn-outline rep-btn-sm"><i class="bi bi-zoom-in"></i></button>
                                    <button type="button" id="floorPlanZoomOut" class="rep-btn rep-btn-outline rep-btn-sm"><i class="bi bi-zoom-out"></i></button>
                                    <button type="button" id="floorPlanReset" class="rep-btn rep-btn-outline rep-btn-sm"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    <button type="button" id="floorPlanFullscreen" class="rep-btn rep-btn-outline rep-btn-sm"><i class="bi bi-arrows-fullscreen"></i></button>
                                </div>
                            </div>
                            <div id="floorPlanWrapper" style="position:relative; width:100%; height:480px; border-radius: var(--rep-radius-md); overflow:hidden; background: var(--rep-bg); border:1px solid var(--rep-border);">
                                <canvas id="floorPlanCanvas" data-floor-plan-src="{{ asset('storage/' . $property->floor_plan_image) }}" style="width:100%; height:100%; cursor:grab;"></canvas>
                            </div>
                            <p class="rep-small mt-2 mb-0"><i class="bi bi-info-circle"></i> Scroll to zoom, drag to pan around the floor plan.</p>
                        </div>
                    @endif

                    {{-- ============ AMENITIES ============ --}}
                    <div class="rep-card p-4 mb-4" data-aos="fade-up">
                        <h3 class="rep-h4 mb-3">Amenities</h3>
                        <div class="row g-3">
                            @forelse($property->amenities as $amenity)
                                <div class="col-6 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi {{ $amenity->icon ?? 'bi-check2-circle' }}" style="color: var(--rep-success);"></i>
                                        <span class="rep-small">{{ $amenity->name }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="rep-small rep-text-muted mb-0">No amenities listed for this property.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- ============ NEARBY LOCATIONS ============ --}}
                    @if(!empty($property->nearby_places))
                        <div class="rep-card p-4 mb-4" data-aos="fade-up">
                            <h3 class="rep-h4 mb-3">What's Nearby</h3>
                            <div class="row g-3">
                                @foreach($property->nearby_places as $place)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: rgba(var(--rep-primary-rgb),0.04);">
                                            <i class="bi {{ $place['icon'] ?? 'bi-geo' }}" style="color: var(--rep-primary);"></i>
                                            <span class="rep-small flex-grow-1">{{ $place['name'] }}</span>
                                            <span class="rep-small rep-text-muted">{{ $place['distance'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ============ MAP ============ --}}
                    <div class="rep-card p-4 mb-4" data-aos="fade-up">
                        <h3 class="rep-h4 mb-3">Location on Map</h3>
                        @if($property->latitude && $property->longitude)
                            <iframe
                                src="https://www.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&output=embed"
                                style="width:100%; height:360px; border:0; border-radius: var(--rep-radius-md);"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Property location map">
                            </iframe>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center rep-text-muted" style="height:280px; background: var(--rep-bg); border-radius: var(--rep-radius-md); border: 1px dashed var(--rep-border);">
                                <i class="bi bi-map" style="font-size:2rem;"></i>
                                <p class="rep-small mt-2 mb-0">Map coordinates not available for this property.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- ============ AGENT INFO ============ --}}
                    <div class="rep-card p-4 mb-4" data-aos="fade-up" style="position: sticky; top: 100px;">
                        <h3 class="rep-h4 mb-3">Listed By</h3>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="rep-avatar-lg" style="width:64px;height:64px;font-size:1.4rem;">
                                @if(optional($property->agent->user)->avatar)
                                    <img src="{{ asset('storage/' . $property->agent->user->avatar) }}" alt="{{ $property->agent->user->name }}">
                                @else
                                    {{ strtoupper(substr(optional($property->agent->user)->name ?? 'A', 0, 1)) }}
                                @endif
                            </span>
                            <div>
                                <p class="fw-semibold mb-0">{{ optional($property->agent->user)->name ?? 'Agent' }}</p>
                                <p class="rep-small rep-text-muted mb-0">{{ $property->agent->agency_name ?? 'Independent Agent' }}</p>
                                @if($property->agent->is_verified)
                                    <span class="rep-small" style="color: var(--rep-success);"><i class="bi bi-patch-check-fill"></i> Verified Agent</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-4">
                            <a href="tel:{{ optional($property->agent->user)->phone }}" class="rep-btn rep-btn-outline rep-btn-sm flex-fill"><i class="bi bi-telephone"></i> Call</a>
                            @if($property->agent->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->agent->whatsapp) }}" target="_blank" class="rep-btn rep-btn-outline rep-btn-sm flex-fill"><i class="bi bi-whatsapp"></i> Chat</a>
                            @endif
                        </div>

                        {{-- ============ ENQUIRY FORM ============ --}}
                        <h3 class="rep-h4 mb-3">Enquire About This Property</h3>
                        <form id="enquiryForm" data-property-id="{{ $property->id }}">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" class="form-control rep-input" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" class="form-control rep-input" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" class="form-control rep-input" placeholder="Your Phone" required>
                            </div>
                            <div class="mb-3">
                                <textarea name="message" rows="4" class="form-control rep-input" placeholder="I'm interested in this property..." required>Hi, I'm interested in "{{ $property->title }}". Please share more details.</textarea>
                            </div>
                            <button type="submit" class="rep-btn rep-btn-primary w-100" id="enquirySubmitBtn">
                                <i class="bi bi-send"></i> Send Enquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ============ RELATED PROPERTIES ============ --}}
            @if(isset($relatedProperties) && $relatedProperties->count())
                <div class="mt-5">
                    <h3 class="rep-h3 mb-4">Similar Properties</h3>
                    <div class="row g-4">
                        @foreach($relatedProperties as $related)
                            <div class="col-md-6 col-lg-4">
                                <x-property-card :property="$related" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 700, once: true });
        window.repGalleryImages = [
            "{{ asset('storage/' . $property->cover_image) }}",
            @foreach($property->galleries as $image)
                "{{ asset('storage/' . $image->image_path) }}",
            @endforeach
        ];
    </script>
    <script src="{{ asset('assets/js/property-details.js') }}"></script>
@endpush
