<?php

namespace App\Mail;

use App\Models\Ebook;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEbookMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ebook $ebook) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '📚 Nouvelle publication disponible : « ' . $this->ebook->title . ' »');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-ebook');
    }
}
