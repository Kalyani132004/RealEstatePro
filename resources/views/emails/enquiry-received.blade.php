@extends('emails.layout')

@section('subject', 'We received your enquiry — RealEstatePro')

@section('content')
    <h1 style="margin:0 0 16px; font-size:20px; font-weight:700; color:#1A202C;">Hi {{ $enquiry->name }},</h1>

    <p style="margin:0 0 16px;">
        Thanks for your interest! Your enquiry about
        <strong>{{ $enquiry->property->title }}</strong> has been sent directly to the listing agent,
        <strong>{{ $enquiry->property->agent->user->name ?? 'the agent' }}</strong>.
        They typically respond within 24 hours.
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F4F6F9; border-radius:12px; margin: 20px 0;">
        <tr>
            <td style="padding: 20px 24px;">
                <p style="margin:0 0 6px; font-size:13px; color:#64748B; text-transform:uppercase; letter-spacing:0.05em;">Your Message</p>
                <p style="margin:0; font-size:15px; color:#1A202C;">{{ $enquiry->message }}</p>
            </td>
        </tr>
    </table>

    @include('emails.partials.button', ['url' => $propertyUrl, 'label' => 'View Property'])

    <p style="margin:24px 0 0; font-size:13px; color:#64748B;">
        If you didn't submit this enquiry, you can safely ignore this email.
    </p>
@endsection
