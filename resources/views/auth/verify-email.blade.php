{{--
    Email Verification Notice Page
    Route: GET /email/verify -> VerificationController@notice (Phase 13)
    Resend button submits to: POST /email/verification-notification (throttled, Laravel Mail)
--}}
@extends('layouts.auth')

@section('title', 'Verify Your Email')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width:72px;height:72px; background: rgba(var(--rep-accent-rgb),0.2);">
        <i class="bi bi-envelope-check" style="font-size:2rem; color: #9c6f0f;"></i>
    </div>

    <h1 class="rep-h3 mb-1 text-center">Verify your email address</h1>
    <p class="rep-small mb-4 text-center">
        We've sent a verification link to <strong>{{ auth()->user()->email ?? 'your email address' }}</strong>.
        Please click the link to activate your account.
    </p>

    @if (session('resent'))
        <div class="alert alert-success rep-small" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="rep-btn rep-btn-primary w-100 rep-btn-lg">
            <i class="bi bi-arrow-repeat"></i> Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center">
        @csrf
        <button type="submit" class="btn btn-link rep-small text-decoration-none">
            <i class="bi bi-box-arrow-right"></i> Log out
        </button>
    </form>
@endsection
