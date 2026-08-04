@extends('emails.layout')

@section('subject', 'New enquiry on ' . $enquiry->property->title)

@section('content')
    <h1 style="margin:0 0 16px; font-size:20px; font-weight:700; color:#1A202C;">You have a new lead! 🎉</h1>

    <p style="margin:0 0 20px;">
        Someone is interested in your listing <strong>{{ $enquiry->property->title }}</strong>. Here are their details:
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F4F6F9; border-radius:12px; margin: 0 0 20px;">
        <tr>
            <td style="padding: 20px 24px;">
                <p style="margin:0 0 10px; font-size:15px;"><strong>Name:</strong> {{ $enquiry->name }}</p>
                <p style="margin:0 0 10px; font-size:15px;"><strong>Email:</strong> <a href="mailto:{{ $enquiry->email }}" style="color:#0EA5A0;">{{ $enquiry->email }}</a></p>
                <p style="margin:0 0 10px; font-size:15px;"><strong>Phone:</strong> <a href="tel:{{ $enquiry->phone }}" style="color:#0EA5A0;">{{ $enquiry->phone }}</a></p>
                <p style="margin:0; font-size:15px;"><strong>Message:</strong><br>{{ $enquiry->message }}</p>
            </td>
        </tr>
    </table>

    @include('emails.partials.button', ['url' => $agentEnquiriesUrl, 'label' => 'Respond to This Lead'])

    <p style="margin:24px 0 0; font-size:13px; color:#64748B;">
        Tip: leads respond best when contacted within the first hour. Update the enquiry status once you've reached out.
    </p>
@endsection
