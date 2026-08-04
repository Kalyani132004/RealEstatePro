{{--
    Forgot Password Page
    Route: GET /forgot-password -> ForgotPasswordController@create
    Submits to: POST /forgot-password -> ForgotPasswordController@store (Phase 13, uses Laravel Mail)
--}}
@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width:64px;height:64px; background: rgba(var(--rep-secondary-rgb),0.1);">
        <i class="bi bi-key" style="font-size:1.6rem; color: var(--rep-secondary);"></i>
    </div>

    <h1 class="rep-h3 mb-1 text-center">Forgot your password?</h1>
    <p class="rep-small mb-4 text-center">
        No worries. Enter the email associated with your account and we'll send you a link to reset it.
    </p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label class="form-label rep-small">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control rep-input @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="rep-btn rep-btn-primary w-100 rep-btn-lg">Send Reset Link <i class="bi bi-send"></i></button>
    </form>

    <p class="text-center rep-small mt-4 mb-0">
        Remembered it after all? <a href="{{ route('login') }}" class="fw-semibold">Back to Sign In</a>
    </p>
@endsection
