{{--
    Reusable property card.
    Usage: <x-property-card :property="$property" />
    Expects a Property model instance with relationships: category, location, agent
--}}
@props(['property'])

<div class="rep-card h-100 rep-hover-lift" data-aos="fade-up">
    <div class="rep-card-img-wrap">
        <a href="{{ route('properties.show', $property->slug) }}">
            <img
                src="{{ asset('storage/' . $property->cover_image) }}"
                srcset="{{ asset('storage/' . $property->cover_image) }} 800w, {{ asset('storage/' . $property->cover_image) }} 400w"
                sizes="(max-width: 576px) 100vw, (max-width: 992px) 50vw, 33vw"
                alt="{{ $property->title }}"
                loading="lazy"
            >
        </a>

        <div class="position-absolute top-0 start-0 p-3 d-flex gap-2">
            @if($property->is_featured)
                <span class="rep-badge rep-badge-featured"><i class="bi bi-star-fill"></i> Featured</span>
            @endif
            <span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
        </div>

        <button
            type="button"
            class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 rep-wishlist-btn"
            data-property-id="{{ $property->id }}"
            aria-label="Save property"
            style="width:38px;height:38px;"
        >
            <i class="bi {{ auth()->check() && auth()->user()->savedProperties->contains($property->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
        </button>

        <span class="position-absolute bottom-0 start-0 m-3 rep-price px-3 py-1 rounded-pill" style="background: var(--rep-glass); backdrop-filter: blur(10px);">
            ₹{{ number_format($property->price) }}{{ $property->listing_type === 'rent' ? '/mo' : '' }}
        </span>
    </div>

    <div class="rep-card-body">
        <p class="rep-small text-uppercase mb-1" style="color: var(--rep-secondary); letter-spacing:0.05em;">
            {{ $property->category->name ?? 'Property' }}
        </p>
        <h3 class="rep-h4 mb-2">
            <a href="{{ route('properties.show', $property->slug) }}" class="text-decoration-none" style="color: var(--rep-text);">
                {{ Str::limit($property->title, 42) }}
            </a>
        </h3>
        <p class="rep-small mb-3">
            <i class="bi bi-geo-alt me-1"></i>
            {{ $property->location->city ?? '' }}, {{ $property->location->state ?? '' }}
        </p>

        <div class="d-flex justify-content-between border-top pt-3" style="border-color: var(--rep-border) !important;">
            <span class="rep-small"><i class="bi bi-door-closed me-1"></i>{{ $property->bedrooms }} Beds</span>
            <span class="rep-small"><i class="bi bi-droplet me-1"></i>{{ $property->bathrooms }} Baths</span>
            <span class="rep-small"><i class="bi bi-arrows-angle-expand me-1"></i>{{ number_format($property->area_sqft) }} sqft</span>
        </div>
    </div>
</div>
