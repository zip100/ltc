<?php

namespace App\Jobs;

use App\Module\Huobi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BtcOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $processer, $info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->processer = new Huobi();
        $this->info = $this->processer->queryOrder($orderId, Huobi::CONIN_BTC);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // 0未成交　1部分成交　2已完成　3已取消 4废弃（该状态已不再使用） 5异常 6部分成交已取消 7队列中

        if ($this->info == '0' || $this->info == '1') {
            $this->release();
        }
        if ($this->info == '2') {
           // $this->processer->sale($price, $amount, Huobi::CONIN_BTC);
        }
    }
}
