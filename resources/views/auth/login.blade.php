{{--
    Login Page
    Route: GET /login -> LoginController@create
    Submits to: POST /login -> LoginController@store (Phase 13)
--}}
@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
    <h1 class="rep-h3 mb-1">Welcome back</h1>
    <p class="rep-small mb-4">Sign in to manage your saved properties, enquiries, and listings.</p>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label class="form-label rep-small">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control rep-input @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-2">
            <label class="form-label rep-small">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control rep-input @error('password') is-invalid @enderror" placeholder="Your password" required>
                <button class="btn rep-input rep-toggle-password" type="button" data-target="password"><i class="bi bi-eye"></i></button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label rep-small" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="rep-small">Forgot password?</a>
        </div>

        <button type="submit" class="rep-btn rep-btn-primary w-100 rep-btn-lg">Sign In <i class="bi bi-arrow-right"></i></button>
    </form>

    <p class="text-center rep-small mt-4 mb-0">
        Don't have an account? <a href="{{ route('register') }}" class="fw-semibold">Create one</a>
    </p>
@endsection
