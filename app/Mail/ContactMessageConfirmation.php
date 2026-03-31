<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your message | PH Haiyan Advocacy Inc.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message-confirmation',
            with: [
                'contactMessage' => $this->contactMessage,
                'inquiryLabel' => config('site.inquiry_types.'.$this->contactMessage->inquiry_type, $this->contactMessage->inquiry_type),
            ],
        );
    }
}
