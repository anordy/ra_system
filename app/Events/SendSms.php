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
    public $extra;

    public function __construct($service, $tokenId, $extra = [])
    {
        $this->service = $service;
        $this->tokenId = $tokenId;
        $this->extra = $extra;
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
