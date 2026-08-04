@extends('emails.layout')

@section('subject', 'Welcome to RealEstatePro!')

@section('content')
    <h1 style="margin:0 0 16px; font-size:20px; font-weight:700; color:#1A202C;">Welcome aboard, {{ $user->name }}! 👋</h1>

    @if($user->isAgent())
        <p style="margin:0 0 16px;">
            Your agent account has been created. Once you verify your email, you can start listing
            properties, uploading virtual tours and floor plans, and managing enquiries from interested buyers and renters.
        </p>
        <p style="margin:0 0 16px;">
            Our team may also review your agency details to award a "Verified Agent" badge, which helps
            build trust with prospective clients.
        </p>
    @else
        <p style="margin:0 0 16px;">
            Your account has been created. Once you verify your email, you'll be able to save your
            favorite properties, submit enquiries to agents, and track responses — all from your dashboard.
        </p>
    @endif

    @include('emails.partials.button', ['url' => $dashboardUrl, 'label' => 'Go to My Dashboard'])

    <p style="margin:24px 0 0; font-size:13px; color:#64748B;">
        Need help getting started? Just reply to our support team any time.
    </p>
@endsection
