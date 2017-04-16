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

        // 当前价格
        $nowPrice = $handle->getBtcPrice();

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
        $res = $handle->buy($buyPrice, $buyCount, Huobi::CONIN_BTC);

        if ($res['result'] == 'success') {
            $job = new BtcOrder($res['id']);
            dispatch($job);
        }

    }
}
