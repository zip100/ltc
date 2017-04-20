<?php

namespace App\Jobs;

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
    public function __construct($id, $type)
    {
        if ($type == Btc::FLAG) {
            $this->order = Btc::getInstance()->cancelOrder($id);
        }
        if ($type == Ltc::FLAG) {
            $this->order = Ltc::getInstance()->cancelOrder($id);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 交易完成
        if ($this->order['status'] == 2) {
            \Log::info('[Buy][Success] id:'. $this->order->id);
            $salePrice = $this->order['order_price'] + 100;
            $saleAmount = round($this->order['order_amount'] - $this->order['order_amount'] * 0.002, 4);
            Btc::getInstance()->saleCoins($salePrice, $saleAmount);
            return;
        }
    }
}
