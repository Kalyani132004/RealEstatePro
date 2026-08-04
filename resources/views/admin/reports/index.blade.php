{{--
    Admin — Reports
    Route: GET /admin/reports -> Admin\ReportController@index
    Expected data:
      $monthlyListings -> ['labels' => [...12 months], 'data' => [...]]
      $listingsByType  -> ['labels' => ['Sale','Rent'], 'data' => [...]]
      $topAgents       -> Collection<Agent> orderBy properties_count desc, limit 5
      $topLocations    -> Collection<Location> orderBy properties_count desc, limit 5
--}}
@extends('layouts.dashboard')

@section('page-title', 'Reports')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Reports &amp; Analytics</h2>
            <p class="rep-small mb-0">Platform performance at a glance.</p>
        </div>
        <button type="button" class="rep-btn rep-btn-outline" onclick="window.print()"><i class="bi bi-download"></i> Export / Print</button>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="rep-card p-4 h-100">
                <h3 class="rep-h4 mb-3">Listings Added — Last 12 Months</h3>
                <canvas id="monthlyListingsChart" height="240"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="rep-card p-4 h-100">
                <h3 class="rep-h4 mb-3">Sale vs Rent</h3>
                <canvas id="listingsByTypeChart" height="240"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="rep-card p-4">
                <h3 class="rep-h4 mb-3">Top Performing Agents</h3>
                <table class="table align-middle mb-0">
                    <thead><tr class="rep-small text-uppercase"><th>Agent</th><th>Listings</th><th>Rating</th></tr></thead>
                    <tbody>
                        @forelse($topAgents ?? [] as $agent)
                            <tr>
                                <td class="rep-small">{{ $agent->user->name ?? '—' }}</td>
                                <td class="rep-small">{{ $agent->properties_count ?? 0 }}</td>
                                <td class="rep-small"><i class="bi bi-star-fill" style="color: var(--rep-accent);"></i> {{ number_format($agent->rating ?? 0, 1) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center rep-text-muted py-4">No data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rep-card p-4">
                <h3 class="rep-h4 mb-3">Top Locations</h3>
                <table class="table align-middle mb-0">
                    <thead><tr class="rep-small text-uppercase"><th>City</th><th>State</th><th>Listings</th></tr></thead>
                    <tbody>
                        @forelse($topLocations ?? [] as $location)
                            <tr>
                                <td class="rep-small">{{ $location->city }}</td>
                                <td class="rep-small">{{ $location->state }}</td>
                                <td class="rep-small">{{ $location->properties_count ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center rep-text-muted py-4">No data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        window.repReportData = {
            monthlyLabels: @json($monthlyListings['labels'] ?? []),
            monthlyData: @json($monthlyListings['data'] ?? []),
            typeLabels: @json($listingsByType['labels'] ?? ['Sale', 'Rent']),
            typeData: @json($listingsByType['data'] ?? [0, 0]),
        };
    </script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
