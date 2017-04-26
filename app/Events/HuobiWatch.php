<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HuobiWatch
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    // 系统启动
    const ACTION_START = 1;

    public $actMap = [
        self::ACTION_START => '系统启动'
    ];

    public $message = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($act)
    {
        $this->message = $this->actMap[$act];
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
