{{--
    Admin — Manage Enquiries (platform-wide)
    Route: GET /admin/enquiries -> Admin\EnquiryController@index
    Expected data: $enquiries -> paginated Collection<Enquiry> (all agents, with property, agent)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Enquiries')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Manage Enquiries</h2>
        <p class="rep-small mb-0">Every enquiry submitted across all properties and agents.</p>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">Enquirer</th>
                        <th>Property</th>
                        <th>Agent</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries ?? [] as $enquiry)
                        <tr>
                            <td class="ps-4">
                                <p class="fw-semibold rep-small mb-0">{{ $enquiry->name }}</p>
                                <p class="rep-small rep-text-muted mb-0">{{ $enquiry->email }}</p>
                            </td>
                            <td class="rep-small">{{ Str::limit($enquiry->property->title ?? '—', 28) }}</td>
                            <td class="rep-small">{{ $enquiry->agent->user->name ?? '—' }}</td>
                            <td><span class="rep-badge rep-badge-{{ $enquiry->status === 'new' ? 'pending' : ($enquiry->status === 'closed' ? 'sold' : 'available') }}">{{ ucfirst($enquiry->status) }}</span></td>
                            <td class="rep-small">{{ $enquiry->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <button type="button" class="rep-btn rep-btn-outline rep-btn-sm" data-bs-toggle="modal" data-bs-target="#enquiryModal{{ $enquiry->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <div class="modal fade" id="enquiryModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rep-card border-0 p-2">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title rep-h4">Enquiry from {{ $enquiry->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="rep-small mb-1"><strong>Email:</strong> {{ $enquiry->email }}</p>
                                                <p class="rep-small mb-1"><strong>Phone:</strong> {{ $enquiry->phone }}</p>
                                                <p class="rep-small mb-1"><strong>Property:</strong> {{ $enquiry->property->title ?? '—' }}</p>
                                                <p class="rep-small mb-3"><strong>Agent:</strong> {{ $enquiry->agent->user->name ?? '—' }}</p>
                                                <p class="rep-body">{{ $enquiry->message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 rep-text-muted">No enquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($enquiries) && $enquiries->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $enquiries->links() }}</div>
    @endif

@endsection
