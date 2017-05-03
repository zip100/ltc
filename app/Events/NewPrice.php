<?php

namespace App\Events;

use App\Model\Huobi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewPrice extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

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
        return [
            "huobi-price"
        ];
    }
}
