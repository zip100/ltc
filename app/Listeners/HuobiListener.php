<?php

namespace App\Listeners;

use App\Events\HuobiPrice;
use App\Jobs\OrderQuery;
use App\Model\Notice;
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
        $events->listen(
            'App\Events\NewPrice',
            'App\Listeners\HuobiListener@newPrice'
        );
    }

    public function priceNotice($event)
    {
        $huobi = $event->huobi;
        $amount = \App\Model\Huobi::where('type', $huobi->type)->where('created_at', '>', date('Y-m-d H:i:s', strtotime($huobi->created_at) - $event->time))->sum('amount');

        $map = [
            Api::CONIN_BTC => 'BTC',
            Api::CONIN_LTC => 'LTC'
        ];

        if (autoBuyLtc() && $huobi->type == Ltc::FLAG && $amount <= -2) {

            // 期待买入100个
            $str = $this->buyLtc($event->huobi->price-0.5, 100);

            $sms['content'] = sprintf('[尝试自动购买]最近%s分钟浮动%s当前%s,', ($event->time / 60), $amount, $event->huobi->price) . $str;
        } else {
            $sms['content'] = sprintf('[自动购买关闭]最近%s分钟浮动%s当前%s', ($event->time / 60), $amount, $event->huobi->price);
        }


        $sms['type'] = $map[$huobi->type];

        Api::sendSms('18610009545', $sms);
    }

    public function buyLtc($price, $amount)
    {
        $money = Api::getInstance()->getMoney();
        $needMoney = $price * $amount;

        if ($needMoney > $money) {
            $buyAmount = parseMoney($money / $price);
        } else {
            $buyAmount = $amount;
        }


        $buyPrice = $price;
        $sellPrice = $buyPrice + 1.5;

        $res = Ltc::getInstance()->buyCoins($buyPrice, $buyAmount);
        if (isset($res['result']) && $res['result'] == 'success') {
            $order = Order::forceCreate([
                'type' => Ltc::FLAG,
                'buy_price' => $res['data']['price'],
                'buy_amount' => $res['data']['amount'],
                'buy_money' => $res['data']['money'],
                'buy_id' => $res['id'],
                'sell_price' => $sellPrice,
                'sell_amount' => $res['data']['amount'],
                'sell_money' => 0,
                'sell_id' => 0,
                'sell_status' => 0,
                'buy_status' => 0
            ]);

            $job = new OrderQuery($order->id);
            dispatch($job);
            return sprintf('[买入挂单][成功][价格:%s][数量:%s]', $buyPrice, $buyAmount);
        } else {
            return sprintf('[买入挂单][失败][价格:%s][数量:%s]', $buyPrice, $buyAmount);
        }


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
        $sms['content'] = sprintf('买入数量:%d,买入价格:%d', $event->order->buy_amount, $event->order->buy_price);

        if ($event->order->sell_price > 0) {
            $sellPrice = $event->order->sell_price;
            $sellAmount = $event->order->sell_amount;

            $instance = $event->order->type == Btc::FLAG ? Btc::getInstance() : Ltc::getInstance();
            $res = $instance->saleCoins($sellPrice, $sellAmount);

            if (isset($res['result']) && $res['result'] == 'success') {

                $event->order->sell_id = $res['id'];
                $event->order->save();

                $job = new OrderQuery($event->order->id);
                dispatch($job);

                $sms['content'] = sprintf('买入数量:%s,买入价格:%s,挂单价格:%s,挂单数量:%s', $event->order->buy_amount, $event->order->buy_price, $sellPrice, $sellAmount);

            } else {

                $sms['content'] = sprintf('买入数量:%s,买入价格:%s,挂单价格:%s,挂单数量:%s,挂单失败', $event->order->buy_amount, $event->order->buy_price, $sellPrice, $sellAmount);
            }

        }

        $sms['type'] = $map[$event->order->type];
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

    public function newPrice($event)
    {
        $price = $event->huobi->price;
        $lists = Notice::where('status', Notice::STATUS_WAIT)->where('type', $event->huobi->type)->get();


        if ($lists) {
            foreach ($lists as $row) {
                $result = false;
                $operator = $row->operator == '=' ? '==' : $row->operator;
                eval(sprintf('$result=%s %s %s;', $price, $operator, $row->price));
                if ($result) {
                    $row->fire();

                    Api::sendSms($row->mobile, [
                        'type' => $event->huobi->type,
                        'content' => sprintf('当前价格%s%s%s', $price, $row->operator, $row->price)
                    ]);

                    event(new \App\Events\PriceNotice($row));
                }
            }
        }
    }

}
