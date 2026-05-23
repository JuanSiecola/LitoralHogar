<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva consulta desde LitoralHogar — ' . $this->data['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact',
            with: ['data' => $this->data],
        );
    }
}