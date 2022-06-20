<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSms
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tokenId;
    public $service;
    public function __construct($service, $tokenId)
    {
        $this->service = $service;
        $this->tokenId = $tokenId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
