<?php

namespace App\Jobs;

use App\Model\Btc;
use App\Module\Huobi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BuyBtc implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $handle = new Huobi();

        $config = config('huobi.btc');


        \Log::info(sprintf('[StartBuyBtcJob]'));

        // 价格阀值
        $priceLimit = $config['price_limit'] * 100;
        // 当前价格
        $nowPrice = $handle->getBtcPrice();

        if ($nowPrice > $priceLimit) {
            \Log::info(sprintf('[CancelBuy] 当前价格 %s 大于价格阀值  %s 放弃购买!', ($nowPrice / 100), $config['price_limit']));
            return;
        } else {
            \Log::info(sprintf('[Buy] 当前价格 %s 小于于价格阀值  %s 继续购买!', ($nowPrice / 100), $config['price_limit']));
        }


        // 最近一个小时的降幅
        $amount = \App\Model\Btc::where('created_at', '>', date('Y-m-d H:i:s', time() - $config['time_offset']))->sum('amount');
        if ($amount > ($config['price_lower'] * 100)) {
            \Log::info(sprintf('[CancelBuy] 最近一小时降幅为 %s 未达到 %s 放弃购买!', ($amount / 100), $config['price_lower']));
            return;
        } else {
            \Log::info(sprintf('[Buy] 最近一小时降幅为 %s 已达到 %s 继续购买!', ($amount / 100), $config['price_lower']));
        }

        // 账户余额
        $money = $handle->getMoney();
        if ($money < 100) {
            \Log::info(sprintf('[CancelBuy] 账户余额不足(%s) 放弃购买', $money));
            reutrn;
        } else {
            \Log::info(sprintf('[Buy] 账户余额充足(%s) 继续购买!', $money));
        }

        $money = $money * 100;

        // 当前价格减去10块作为买入价格
        $buyPrice = $nowPrice - 1000;
        // 当前可用金额除以单价全额买入
        $buyCount = round($money / $buyPrice, 4);


        \Log::info(sprintf('[Buy] 单价:%s 数量:%s', $buyPrice, $buyCount));
        // $handle->buy($buyPrice, $buyCount, Huobi::CONIN_BTC);
    }
}
