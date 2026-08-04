{{--
    Admin — Manage Properties
    Route: GET /admin/properties -> Admin\PropertyController@index
    Feature toggle: PATCH /admin/properties/{property}/feature -> Admin\PropertyController@toggleFeature
    Delete: DELETE /admin/properties/{property} -> Admin\PropertyController@destroy
    Expected data: $properties -> paginated Collection<Property> (all agents, with agent/category)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Properties')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Manage Properties</h2>
            <p class="rep-small mb-0">Every property listed across all agents on the platform.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select rep-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Rented</option>
            </select>
        </form>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">Property</th>
                        <th>Agent</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties ?? [] as $property)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $property->cover_image) }}" alt="" style="width:48px;height:48px;border-radius:var(--rep-radius-sm);object-fit:cover;">
                                    <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="fw-semibold rep-small text-decoration-none" style="color: var(--rep-text);">
                                        {{ Str::limit($property->title, 32) }}
                                    </a>
                                </div>
                            </td>
                            <td class="rep-small">{{ $property->agent->user->name ?? '—' }}</td>
                            <td class="rep-price rep-small">₹{{ number_format($property->price) }}</td>
                            <td><span class="rep-badge rep-badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.properties.toggle-feature', $property->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Toggle featured">
                                        <i class="bi {{ $property->is_featured ? 'bi-star-fill' : 'bi-star' }}" style="color: var(--rep-accent); font-size:1.2rem;"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <form method="POST" action="{{ route('admin.properties.destroy', $property->id) }}" data-confirm="Delete this property permanently?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm" style="color: var(--rep-danger); border-color: var(--rep-danger);">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 rep-text-muted">No properties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($properties) && $properties->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $properties->links() }}</div>
    @endif

@endsection
