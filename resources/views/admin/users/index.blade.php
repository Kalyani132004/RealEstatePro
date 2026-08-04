{{--
    Admin — Manage Users
    Route: GET /admin/users -> Admin\UserController@index
    Block/Unblock: PATCH /admin/users/{user}/status -> Admin\UserController@toggleStatus
    Delete: DELETE /admin/users/{user} -> Admin\UserController@destroy
    Expected data: $users -> paginated Collection<User> (role = user)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Users')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Manage Users</h2>
            <p class="rep-small mb-0">All registered buyers and renters on the platform.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control rep-input" placeholder="Search by name or email...">
            <button type="submit" class="rep-btn rep-btn-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="rep-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="rep-small text-uppercase" style="background: rgba(var(--rep-primary-rgb),0.04);">
                        <th class="ps-4">User</th>
                        <th>Phone</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rep-avatar-sm" style="background: rgba(var(--rep-secondary-rgb),0.15); color:var(--rep-secondary);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold rep-small mb-0">{{ $user->name }}</p>
                                        <p class="rep-small rep-text-muted mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="rep-small">{{ $user->phone ?? '—' }}</td>
                            <td class="rep-small">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="rep-badge rep-badge-{{ $user->status === 'active' ? 'available' : 'sold' }}">{{ ucfirst($user->status) }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" data-confirm="{{ $user->status === 'active' ? 'Block this user?' : 'Unblock this user?' }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm">
                                            <i class="bi {{ $user->status === 'active' ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                            {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" data-confirm="Delete this user permanently?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm" style="color: var(--rep-danger); border-color: var(--rep-danger);">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 rep-text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($users) && $users->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $users->links() }}</div>
    @endif

@endsection
