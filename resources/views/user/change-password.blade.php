{{--
    User Dashboard — Change Password
    Route: GET /user/password -> User\PasswordController@edit
    Submits to: PUT /user/password -> User\PasswordController@update (Phase 12/13)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Change Password')

@section('sidebar')
    <x-sidebar-user />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">Change Password</h2>
        <p class="rep-small mb-0">Choose a strong password you haven't used elsewhere.</p>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="rep-card p-4">
                <form method="POST" action="{{ route('user.password.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label rep-small">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control rep-input @error('current_password') is-invalid @enderror" required>
                            <button class="btn rep-input rep-toggle-password" type="button" data-target="current_password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label rep-small">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control rep-input @error('password') is-invalid @enderror" required>
                            <button class="btn rep-input rep-toggle-password" type="button" data-target="password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label rep-small">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rep-input" required>
                            <button class="btn rep-input rep-toggle-password" type="button" data-target="password_confirmation"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="rep-btn rep-btn-primary"><i class="bi bi-shield-check"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
