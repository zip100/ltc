<?php

namespace App\Jobs;

use App\Events\BuyFinish;
use App\Events\SellFinish;
use App\Model\Order;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 0未成交　1部分成交　2已完成　3已取消 4废弃（该状态已不再使用） 5异常 6部分成交已取消 7队列中

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->order = Order::findOrFail($id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order->type == Btc::FLAG) {
            $order = Btc::getInstance()->queryOrder($this->order->buy_id);
        }
        if ($this->order->type == Ltc::FLAG) {
            $order = Ltc::getInstance()->queryOrder($this->order->buy_id);
        }

        if (in_array($order['type'], [1, 3])) {
            $this->order->last_buy_query = date('y-m-d H:i:s', time());
            $this->order->buy_status = $order['status'];
            $this->order->save();

            // 交易完成
            if ($order['status'] == 2) {
                event(new BuyFinish($this->order));
                return;
            }

            if ($order['status'] == 3) {
                return;
            }

            $this->release(2);
        }


        if (in_array($order['type'], [2, 4])) {
            $this->order->last_sell_query = date('y-m-d H:i:s', time());
            $this->order->sell_status = $order['status'];
            $this->order->save();

            // 交易完成
            if ($order['status'] == 2) {
                event(new SellFinish($this->order));

                $this->order->sell_money = $order['total'];
                $this->order->sell_amount = $order['order_amount'];
                $this->order->save();

                return;
            }

            if ($order['status'] == 3) {
                return;
            }

            $this->release(2);
        }
    }

}
