<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccessGrantedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Purchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '✅ Votre accès à « ' . $this->purchase->ebook->title . ' » est activé !');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.access-granted');
    }
}
