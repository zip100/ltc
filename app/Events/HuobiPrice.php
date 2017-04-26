<?php

namespace App\Events;

use App\Model\Huobi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HuobiPrice
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $huobi;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Huobi $huobi)
    {
        $this->huobi = $huobi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
