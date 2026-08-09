{{--
Agent Dashboard — Manage Enquiries
Route: GET /agent/enquiries -> Agent\EnquiryController@index
Status update: PATCH /agent/enquiries/{enquiry}/status -> Agent\EnquiryController@updateStatus
Expected data: $enquiries -> paginated Collection (agent's own, with property relation)
--}}

@extends('layouts.dashboard')

@section('page-title', 'Manage Enquiries')

@section('sidebar')
    <x-sidebar-agent />
@endsection

@section('content')

<div class="mb-4">
    <h2 class="rep-h3 mb-1">Manage Enquiries</h2>
    <p class="rep-small mb-0">
        Respond to leads and update their status as you follow up.
    </p>
</div>

<div class="rep-card p-0 overflow-hidden">

    @forelse($enquiries ?? [] as $enquiry)

        <div class="d-flex flex-column flex-lg-row gap-3 p-4 {{ !$loop->last ? 'border-bottom' : '' }}"
             style="border-color: var(--rep-border) !important;">

            {{-- Avatar --}}
            <div class="rep-avatar-sm flex-shrink-0"
                 style="width:48px;height:48px;background:rgba(var(--rep-secondary-rgb),0.15);color:var(--rep-secondary);">

                {{ strtoupper(substr($enquiry->name, 0, 1)) }}

            </div>

            <div class="flex-grow-1">

                <div class="d-flex flex-wrap justify-content-between gap-2">

                    <div>

                        <p class="fw-semibold mb-0">
                            {{ $enquiry->name }}
                        </p>

                        <p class="rep-small rep-text-muted mb-1">

                            <i class="bi bi-envelope"></i>
                            {{ $enquiry->email }}

                            &nbsp;|&nbsp;

                            <i class="bi bi-telephone"></i>
                            {{ $enquiry->phone }}

                        </p>

                    </div>

                    {{-- DATE & TIME - IST --}}
                    <p class="rep-small mb-0">

                        <i class="bi bi-calendar3"></i>

                        {{ $enquiry->created_at
                            ->timezone('Asia/Kolkata')
                            ->format('d M Y, h:i A') }}

                    </p>

                </div>


                {{-- Property --}}
                <p class="rep-small mb-2">

                    Regarding:

                    <a href="{{ route('properties.show', $enquiry->property->slug ?? '') }}"
                       target="_blank"
                       class="fw-semibold">

                        {{ $enquiry->property->title ?? 'Property' }}

                    </a>

                </p>


                {{-- Message --}}
                <p class="rep-body mb-3">
                    {{ $enquiry->message }}
                </p>


                {{-- Actions --}}
                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <select
                        class="form-select rep-select rep-enquiry-status w-auto"
                        data-enquiry-id="{{ $enquiry->id }}">

                        <option value="new"
                            {{ $enquiry->status === 'new' ? 'selected' : '' }}>
                            New
                        </option>

                        <option value="contacted"
                            {{ $enquiry->status === 'contacted' ? 'selected' : '' }}>
                            Contacted
                        </option>

                        <option value="closed"
                            {{ $enquiry->status === 'closed' ? 'selected' : '' }}>
                            Closed
                        </option>

                    </select>


                    {{-- Email --}}
                    <a href="mailto:{{ $enquiry->email }}"
                       class="rep-btn rep-btn-outline rep-btn-sm">

                        <i class="bi bi-envelope"></i>
                        Email

                    </a>


                    {{-- Call --}}
                    <a href="tel:{{ $enquiry->phone }}"
                       class="rep-btn rep-btn-outline rep-btn-sm">

                        <i class="bi bi-telephone"></i>
                        Call

                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="text-center p-5">

            <i class="bi bi-chat-square-text"
               style="font-size:2.5rem;color:var(--rep-text-muted);">
            </i>

            <h3 class="rep-h4 mt-3">
                No enquiries yet
            </h3>

            <p class="rep-small mb-0">
                Enquiries from interested buyers/renters will show up here.
            </p>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if(isset($enquiries) && $enquiries->hasPages())

    <div class="mt-4 d-flex justify-content-center">

        {{ $enquiries->links() }}

    </div>

@endif

@endsection


@push('scripts')

@endpush