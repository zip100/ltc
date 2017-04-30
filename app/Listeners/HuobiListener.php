<?php

namespace App\Listeners;

use App\Events\HuobiPrice;
use App\Jobs\OrderQuery;
use App\Module\Huobi\Api;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HuobiListener implements ShouldQueue
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
            'App\Listeners\HuobiListener@priceNotice'
        );
        $events->listen(
            'App\Events\HuobiWatch',
            'App\Listeners\HuobiListener@huobiWatch'
        );
        $events->listen(
            'App\Events\BuyFinish',
            'App\Listeners\HuobiListener@buyFinish'
        );
        $events->listen(
            'App\Events\SellFinish',
            'App\Listeners\HuobiListener@sellFinish'
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

    public function buyFinish($event)
    {
        $map = [
            Api::CONIN_BTC => 'BTC',
            Api::CONIN_LTC => 'LTC'
        ];

        if ($event->order->sell_price > 0) {
            $sellPrice = $event->order->sell_price;
            $sellAmount = $event->order->buy_amount;

            $instance = $event->order->type == Btc::FLAG ? Btc::getInstance() : Ltc::getInstance();
            $res = $instance->saleCoins($sellPrice, $sellAmount);

            if (isset($res['result']) && $res['result'] == 'success') {

                $event->order->sell_id = $res['id'];
                $event->order->save();

                $job = new OrderQuery($event->order->id);
                dispatch($job);
            }

        }

        $sms['type'] = $map[$event->order->type];
        $sms['content'] = sprintf('买入数量:%d,挂单价格:%d', $event->order->buy_amount, $event->order->buy_price);
        Api::sendSms('18610009545', $sms);
    }

    public function sellFinish($event)
    {
        $map = [
            Api::CONIN_BTC => 'BTC',
            Api::CONIN_LTC => 'LTC'
        ];
        $sms['type'] = $map[$event->order->type];
        $sms['content'] = sprintf('卖出完成,价格:%d,金额:%d', $event->order->sell_price, $event->order->sell_money);
        Api::sendSms('18610009545', $sms);
    }

}
