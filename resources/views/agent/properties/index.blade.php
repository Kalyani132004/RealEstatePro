{{--
    Agent Dashboard — My Properties (list / manage)
    Route: GET /agent/properties -> Agent\PropertyController@index
    Expected data: $properties -> paginated Collection<Property> (agent's own, with category/location)
--}}
@extends('layouts.dashboard')

@section('page-title', 'My Properties')

@section('sidebar')
    <x-sidebar-agent />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">My Properties</h2>
            <p class="rep-small mb-0">Manage all the properties you've listed on RealEstatePro.</p>
        </div>
        <a href="{{ route('agent.properties.create') }}" class="rep-btn rep-btn-primary"><i class="bi bi-plus-circle"></i> Add New Property</a>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">Property</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties ?? [] as $property)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $property->cover_image) }}" alt="" style="width:52px;height:52px;border-radius:var(--rep-radius-sm);object-fit:cover;">
                                    <div>
                                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="fw-semibold rep-small text-decoration-none" style="color: var(--rep-text);">
                                            {{ Str::limit($property->title, 36) }}
                                        </a>
                                        <p class="rep-small rep-text-muted mb-0"><i class="bi bi-geo-alt"></i> {{ $property->location->city ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="rep-small">{{ $property->category->name ?? '—' }}</td>
                            <td class="rep-price rep-small">₹{{ number_format($property->price) }}</td>
                            <td><span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span></td>
                            <td class="rep-small">{{ $property->views_count ?? 0 }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="rep-btn rep-btn-outline rep-btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('agent.properties.edit', $property->id) }}" class="rep-btn rep-btn-outline rep-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('agent.properties.destroy', $property->id) }}" data-confirm="Delete this property permanently? This cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm" style="color: var(--rep-danger); border-color: var(--rep-danger);" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-buildings" style="font-size:2.5rem; color: var(--rep-text-muted);"></i>
                                <h3 class="rep-h4 mt-3">No properties yet</h3>
                                <p class="rep-small mb-3">Start by adding your first listing.</p>
                                <a href="{{ route('agent.properties.create') }}" class="rep-btn rep-btn-primary mx-auto" style="width:fit-content;">Add Property</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($properties) && $properties->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $properties->links() }}
        </div>
    @endif

@endsection
