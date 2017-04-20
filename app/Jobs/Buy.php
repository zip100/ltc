<?php

namespace App\Jobs;

use App\Model\Huobi;
use App\Module\Huobi\Product\Btc;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Buy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->info = Huobi::findOrFail($id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('[Start][BuyJob]');

        // 比特币
        if ($this->info['type'] == Btc::FLAG) {
            // 最近半个小时幅度
            $amount = Huobi::where('type', Btc::FLAG)->where('created_at', '>', date('Y-m-d H:i:s', time() - 1800))->sum('amount');

            if (($amount < -100 && $this->info['price'] < 7050) || ($amount < -5 && $this->info['price'] < 7000)) {
                $res = Btc::getInstance()->buyCoinsAuto($this->info['price']);

                //  买入成功
                if (isset($res['result']) && $res['result'] == 'success' && isset($res['id']) && $res['id']) {
                    \Log::info('[Buy][Success] id:' . $this->info['id']);
                    $job = new OrderQuery($res['id'], Btc::FLAG);
                    dispatch($job);
                }
            }
        }
    }
}
