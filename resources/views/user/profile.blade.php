{{--
    User Dashboard — Profile
    Route: GET /user/profile -> User\ProfileController@edit
    Submits to: PUT /user/profile -> User\ProfileController@update (ProfileUpdateRequest, Phase 12/14)
    Uses Laravel Storage for avatar upload (Phase 16)
--}}
@extends('layouts.dashboard')

@section('page-title', 'Profile')

@section('sidebar')
    <x-sidebar-user />
@endsection

@section('content')

    <div class="mb-4">
        <h2 class="rep-h3 mb-1">My Profile</h2>
        <p class="rep-small mb-0">Update your personal information and profile photo.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="rep-card p-4 text-center">
                <span class="rep-avatar-lg mx-auto mb-3" id="avatarPreviewWrap">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" id="avatarPreview">
                    @else
                        <span id="avatarPreviewInitial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </span>
                <h3 class="rep-h4 mb-1">{{ auth()->user()->name }}</h3>
                <p class="rep-small rep-text-muted mb-3">{{ auth()->user()->email }}</p>
                <p class="rep-badge rep-badge-available mx-auto" style="width:fit-content;">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="rep-card p-4">
                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label rep-small">Profile Photo</label>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control rep-input @error('avatar') is-invalid @enderror">
                        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label rep-small">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control rep-input @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label rep-small">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control rep-input @error('phone') is-invalid @enderror">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label rep-small">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control rep-input @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if(!auth()->user()->email_verified_at)
                            <p class="rep-small mt-2" style="color: var(--rep-warning);"><i class="bi bi-exclamation-triangle"></i> Your email is not verified.</p>
                        @endif
                    </div>

                    <button type="submit" class="rep-btn rep-btn-primary"><i class="bi bi-check-circle"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('avatarInput').addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                var wrap = document.getElementById('avatarPreviewWrap');
                wrap.innerHTML = '<img src="' + ev.target.result + '" alt="Preview">';
            };
            reader.readAsDataURL(file);
        });
    </script>
@endpush
