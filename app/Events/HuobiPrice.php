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

class HuobiPrice extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $huobi;
    public $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Huobi $huobi, $time = 120)
    {
        $this->huobi = $huobi;
        $this->time = $time;
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
