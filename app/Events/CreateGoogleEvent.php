<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateGoogleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liveConsultationID;
    /**
     * Create a new event instance.
     */
    public function __construct($liveConsultationID)
    {
        $this->liveConsultationID = $liveConsultationID;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
