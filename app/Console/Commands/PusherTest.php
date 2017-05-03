<?php

namespace App\Console\Commands;

use App\Model\Huobi;
use Illuminate\Console\Command;

class PusherTest extends Command
{
    protected $signature = 'pusher:test';

    protected $description = 'Send chat message.';

    public function handle()
    {
        event(new \App\Events\NewPrice(Huobi::findOrfail(100)));
    }
}
