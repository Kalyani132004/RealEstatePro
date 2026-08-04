<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryNotifyAgentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New enquiry on ' . $this->enquiry->property->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enquiry-notify-agent',
            with: [
                'enquiry' => $this->enquiry,
                'agentEnquiriesUrl' => route('agent.enquiries'),
            ],
        );
    }
}
