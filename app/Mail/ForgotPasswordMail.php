<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * ForgotPasswordMail constructor.
     */
    public function __construct($view, $subject, array $data = [])
    {
        $this->view = $view;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject($this->subject)
            ->markdown($this->view)
            ->with($this->data);
    }
}
