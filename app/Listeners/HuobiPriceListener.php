<?php

namespace App\Listeners;

use App\Events\HuobiPrice;
use App\Module\Huobi\Api;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HuobiPriceListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HuobiPrice $event
     * @return void
     */
    public function handle(HuobiPrice $event)
    {
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\HuobiPrice',
            'App\Listeners\HuobiPriceListener@priceNotice'
        );
        $events->listen(
            'App\Events\HuobiWatch',
            'App\Listeners\HuobiPriceListener@huobiWatch'
        );
    }

    public function priceNotice($event)
    {
        $huobi = $event->huobi;
        $amount = \App\Model\Huobi::where('type', $huobi->type)->where('created_at', '>', date('Y-m-d H:i:s', strtotime($huobi->created_at) - 1800))->sum('amount');

        $map = [
            Api::CONIN_BTC => 'BTC',
            Api::CONIN_LTC => 'LTC'
        ];

        $sms['type'] = $map[$huobi->type];
        $sms['content'] = $amount;

        Api::sendSms('18610009545', $sms);
    }

    public function huobiWatch($event)
    {
        Api::sendSms('18610009545', ['type' => 'HuobiWatch', 'content' => $event->message]);
    }

}
