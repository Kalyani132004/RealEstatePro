{{--
    Admin — Manage Locations
    Route: GET /admin/locations -> Admin\LocationController@index
    Store: POST /admin/locations -> Admin\LocationController@store (LocationRequest)
    Update: PUT /admin/locations/{location} -> Admin\LocationController@update
    Delete: DELETE /admin/locations/{location} -> Admin\LocationController@destroy
    Expected data: $locations -> paginated Collection<Location> withCount('properties')
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Locations')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Manage Locations</h2>
            <p class="rep-small mb-0">Cities and regions available in the property search filters.</p>
        </div>
        <button type="button" class="rep-btn rep-btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal" data-mode="create">
            <i class="bi bi-plus-circle"></i> Add Location
        </button>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Properties</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations ?? [] as $location)
                        <tr>
                            <td class="ps-4 fw-semibold rep-small">{{ $location->city }}</td>
                            <td class="rep-small">{{ $location->state }}</td>
                            <td class="rep-small">{{ $location->country }}</td>
                            <td class="rep-small">{{ $location->properties_count ?? 0 }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="rep-btn rep-btn-outline rep-btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#locationModal" data-mode="edit"
                                        data-id="{{ $location->id }}" data-city="{{ $location->city }}"
                                        data-state="{{ $location->state }}" data-country="{{ $location->country }}"
                                        data-zip="{{ $location->zip_code }}"
                                        data-action="{{ route('admin.locations.update', $location->id) }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.locations.destroy', $location->id) }}" data-confirm="Delete this location?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm" style="color: var(--rep-danger); border-color: var(--rep-danger);">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 rep-text-muted">No locations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($locations) && $locations->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $locations->links() }}</div>
    @endif

    {{-- Add / Edit Modal --}}
    <div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rep-card border-0">
                <form method="POST" action="{{ route('admin.locations.store') }}" id="locationForm">
                    @csrf
                    <input type="hidden" name="_method" id="locationMethod" value="POST">

                    <div class="modal-header border-0">
                        <h5 class="modal-title rep-h4" id="locationModalTitle">Add Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label rep-small">City</label>
                                <input type="text" name="city" id="locationCity" class="form-control rep-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label rep-small">State</label>
                                <input type="text" name="state" id="locationState" class="form-control rep-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label rep-small">Country</label>
                                <input type="text" name="country" id="locationCountry" value="India" class="form-control rep-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label rep-small">ZIP / Postal Code</label>
                                <input type="text" name="zip_code" id="locationZip" class="form-control rep-input">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="rep-btn rep-btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="rep-btn rep-btn-primary">Save Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
