<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $view, string $subject, array $data = [])
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
