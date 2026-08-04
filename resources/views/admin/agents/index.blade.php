{{--
    Admin — Manage Agents
    Route: GET /admin/agents -> Admin\AgentController@index
    Verify/Unverify: PATCH /admin/agents/{agent}/verify -> Admin\AgentController@toggleVerify
    Expected data: $agents -> paginated Collection<Agent> (with user, properties_count)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Agents')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Manage Agents</h2>
        <p class="rep-small mb-0">Review and verify agents who list properties on RealEstatePro.</p>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">Agent</th>
                        <th>Agency</th>
                        <th>Listings</th>
                        <th>Experience</th>
                        <th>Verification</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents ?? [] as $agent)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rep-avatar-sm" style="background: rgba(var(--rep-accent-rgb),0.2); color:#9c6f0f;">
                                        {{ strtoupper(substr($agent->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold rep-small mb-0">{{ $agent->user->name ?? '—' }}</p>
                                        <p class="rep-small rep-text-muted mb-0">{{ $agent->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="rep-small">{{ $agent->agency_name ?? '—' }}</td>
                            <td class="rep-small">{{ $agent->properties_count ?? 0 }}</td>
                            <td class="rep-small">{{ $agent->experience_years }} yrs</td>
                            <td>
                                <span class="rep-badge rep-badge-{{ $agent->is_verified ? 'available' : 'pending' }}">
                                    {{ $agent->is_verified ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <form method="POST" action="{{ route('admin.agents.toggle-verify', $agent->id) }}" data-confirm="{{ $agent->is_verified ? 'Revoke verification for this agent?' : 'Verify this agent?' }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rep-btn rep-btn-{{ $agent->is_verified ? 'outline' : 'primary' }} rep-btn-sm">
                                        <i class="bi {{ $agent->is_verified ? 'bi-x-circle' : 'bi-patch-check' }}"></i>
                                        {{ $agent->is_verified ? 'Revoke' : 'Verify' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 rep-text-muted">No agents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($agents) && $agents->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $agents->links() }}</div>
    @endif

@endsection
