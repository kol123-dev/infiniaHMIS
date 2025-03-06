<?php

namespace App\Mail;

use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSent extends Mailable
{
    use Queueable, SerializesModels;

    protected $mail;

    /**
     * Create a new message instance.
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->from(config(''))
            ->subject($this->mail->subject)
            ->markdown('emails.email')
            ->with('mail', $this->mail);
    }
}
