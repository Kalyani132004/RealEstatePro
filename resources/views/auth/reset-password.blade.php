{{--
    Reset Password Page
    Route: GET /reset-password/{token} -> ResetPasswordController@create
    Submits to: POST /reset-password -> ResetPasswordController@store (Phase 13)
--}}
@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="rep-h3 mb-1">Set a new password</h1>
    <p class="rep-small mb-4">Make sure it's at least 8 characters and something you haven't used before.</p>

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

        <div class="mb-3">
            <label class="form-label rep-small">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $email ?? request()->query('email')) }}" class="form-control rep-input @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label rep-small">New Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control rep-input @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                <button class="btn rep-input rep-toggle-password" type="button" data-target="password"><i class="bi bi-eye"></i></button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label rep-small">Confirm New Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rep-input" placeholder="Re-enter new password" required>
                <button class="btn rep-input rep-toggle-password" type="button" data-target="password_confirmation"><i class="bi bi-eye"></i></button>
            </div>
        </div>

        <button type="submit" class="rep-btn rep-btn-primary w-100 rep-btn-lg">Reset Password <i class="bi bi-check-circle"></i></button>
    </form>
@endsection
