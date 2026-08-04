{{--
    Register Page
    Route: GET /register -> RegisterController@create
    Submits to: POST /register -> RegisterController@store (validated by RegisterRequest, Phase 13)
--}}
@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
    <h1 class="rep-h3 mb-1">Create your account</h1>
    <p class="rep-small mb-4">Join RealEstatePro to save listings, contact agents, or list your own properties.</p>

    {{-- Role switch --}}
    <div class="d-flex rep-card p-1 mb-4" role="tablist">
        <label class="flex-fill text-center py-2 mb-0 rounded-pill rep-role-option active" data-role="user" style="cursor:pointer; transition: var(--rep-transition);">
            <input type="radio" name="role" value="user" class="d-none" checked> <i class="bi bi-person"></i> I'm a Buyer/Renter
        </label>
        <label class="flex-fill text-center py-2 mb-0 rounded-pill rep-role-option" data-role="agent" style="cursor:pointer; transition: var(--rep-transition);">
            <input type="radio" name="role" value="agent" class="d-none"> <i class="bi bi-briefcase"></i> I'm an Agent
        </label>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
        @csrf
        <input type="hidden" name="role" id="selectedRole" value="user">

        <div class="mb-3">
            <label class="form-label rep-small">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control rep-input @error('name') is-invalid @enderror" placeholder="John Doe" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label rep-small">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control rep-input @error('email') is-invalid @enderror" placeholder="you@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label rep-small">Phone Number</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control rep-input @error('phone') is-invalid @enderror" placeholder="+91 98765 43210" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Agent-only field, shown/hidden via JS --}}
        <div class="mb-3 d-none" id="agencyNameField">
            <label class="form-label rep-small">Agency Name <span class="rep-text-muted">(optional)</span></label>
            <input type="text" name="agency_name" value="{{ old('agency_name') }}" class="form-control rep-input @error('agency_name') is-invalid @enderror" placeholder="Skyline Realty">
            @error('agency_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label rep-small">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control rep-input @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                <button class="btn rep-input rep-toggle-password" type="button" data-target="password"><i class="bi bi-eye"></i></button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label rep-small">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rep-input" placeholder="Re-enter password" required>
                <button class="btn rep-input rep-toggle-password" type="button" data-target="password_confirmation"><i class="bi bi-eye"></i></button>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
            <label class="form-check-label rep-small" for="terms">
                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" class="rep-btn rep-btn-primary w-100 rep-btn-lg">Create Account <i class="bi bi-arrow-right"></i></button>
    </form>

    <p class="text-center rep-small mt-4 mb-0">
        Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Sign in</a>
    </p>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.rep-role-option').forEach(function (label) {
            label.addEventListener('click', function () {
                document.querySelectorAll('.rep-role-option').forEach(function (l) {
                    l.classList.remove('active');
                    l.style.background = 'transparent';
                });
                this.classList.add('active');
                this.style.background = 'var(--rep-primary)';
                this.style.color = '#fff';

                var role = this.getAttribute('data-role');
                document.getElementById('selectedRole').value = role;
                document.getElementById('agencyNameField').classList.toggle('d-none', role !== 'agent');
            });
        });
        // Restore active style on load (in case of old() role after validation error)
        document.querySelector('.rep-role-option.active').style.background = 'var(--rep-primary)';
        document.querySelector('.rep-role-option.active').style.color = '#fff';
    </script>
@endpush
