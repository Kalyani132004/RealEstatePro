{{--
    Search Results Partial
    Included by: resources/views/properties/search.blade.php (full page load)
    Also returned directly (without layout) by PropertyController@search for
    AJAX requests — see search-filters.js, Phase 15.
--}}
<div id="resultsMeta" class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <p class="rep-small mb-0">
        Showing <strong>{{ $properties->count() }}</strong> of <strong>{{ $properties->total() }}</strong> properties
    </p>
    <select name="sort" form="filterForm" class="form-select rep-select w-auto" onchange="document.getElementById('filterForm').requestSubmit()">
        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
    </select>
</div>

<div id="resultsGrid" class="row g-4">
    @forelse($properties as $property)
        <div class="col-md-6 col-xl-4">
            <x-property-card :property="$property" />
        </div>
    @empty
        <div class="col-12">
            <div class="rep-card text-center p-5">
                <i class="bi bi-search" style="font-size:2.5rem; color: var(--rep-text-muted);"></i>
                <h3 class="rep-h4 mt-3">No properties match your filters</h3>
                <p class="rep-small mb-3">Try adjusting or clearing some filters.</p>
                <a href="{{ route('properties.search') }}" class="rep-btn rep-btn-primary mx-auto" style="width:fit-content;">Clear Filters</a>
            </div>
        </div>
    @endforelse
</div>

@if($properties->hasPages())
    <div id="resultsPagination" class="mt-5 d-flex justify-content-center">
        {{ $properties->appends(request()->query())->links() }}
    </div>
@endif
