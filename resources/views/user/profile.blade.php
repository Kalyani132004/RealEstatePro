@extends('layouts.dashboard')

@section('page-title', 'Profile')

@section('sidebar')
@endsection

@section('content')

<div class="mb-4">
    <h2 class="rep-h3 mb-1">My Profile</h2>
    <p class="rep-small mb-0">
        Update your personal information and profile photo.
    </p>
</div>

<div class="row g-4">

    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="rep-card p-4 text-center">

            <span class="rep-avatar-lg mx-auto mb-3" id="avatarPreviewWrap">

                @if(auth()->user()->avatar)
                    <img
                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                        alt="{{ auth()->user()->name }}"
                        id="avatarPreview"
                        class="img-fluid rounded-circle"
                    >
                @else
                    <span id="avatarPreviewInitial">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </span>
                @endif

            </span>

            <h3 class="rep-h4 mb-1">
                {{ auth()->user()->name }}
            </h3>

            <p class="rep-small rep-text-muted mb-3">
                {{ auth()->user()->email }}
            </p>

            <span class="rep-badge rep-badge-available">
                {{ ucfirst(auth()->user()->role) }}
            </span>

        </div>
    </div>

    <!-- Profile Form -->
    <div class="col-lg-8">

        <div class="rep-card p-4">

            <form
                action="{{ route('user.profile.update') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Profile Photo
                    </label>

                    <input
                        type="file"
                        class="form-control @error('avatar') is-invalid @enderror"
                        name="avatar"
                        id="avatarInput"
                        accept="image/*">

                    @error('avatar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name',auth()->user()->name) }}">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone',auth()->user()->phone) }}">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email',auth()->user()->email) }}">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                @if(!auth()->user()->email_verified_at)

                    <div class="alert alert-warning">
                        Your email address is not verified.
                    </div>

                @endif

                <button class="rep-btn rep-btn-primary">

                    <i class="bi bi-check-circle"></i>

                    Save Changes

                </button>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    let avatarInput = document.getElementById('avatarInput');

    if (!avatarInput) return;

    avatarInput.addEventListener('change', function(e){

        let file = e.target.files[0];

        if(!file) return;

        let reader = new FileReader();

        reader.onload = function(event){

            document.getElementById('avatarPreviewWrap').innerHTML =
            `<img src="${event.target.result}"
                  id="avatarPreview"
                  class="img-fluid rounded-circle"
                  style="width:150px;height:150px;object-fit:cover;">`;

        };

        reader.readAsDataURL(file);

    });

});

</script>

@endpush