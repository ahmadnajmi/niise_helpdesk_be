<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ActionCodeEmail extends Mailable implements  ShouldQueue
{
    use Queueable, SerializesModels;

    public $incident;
    public $email_template;

    public function __construct($incident, $email_template) {
        $this->incident = $incident;
        $this->email_template = $email_template;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->email_template?->sender_email, $this->email_template?->sender_name),
            subject: 'Action Code Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.action_code',
            with:[
                'incident' => $this->incident,
                'email_template' => $this->email_template,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
