<?php

namespace App\Console\Commands;

use App\Jobs\BuyBtc;
use App\Model\Btc;
use App\Module\Huobi;
use Illuminate\Console\Command;

class BtcWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'btc:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastPrice = 0;
        $handdle = new Huobi();

        while (1) {
            $price = $handdle->getBtcPrice();

            if ($price == $lastPrice) {
                continue;
            }

            // 计算本次价格浮动
            $amount = $price - $lastPrice;

            // 第一次运行初始化
            if ($amount == $price) {
                $amount = 0;
            }

            Btc::forceCreate(['price' => $price, 'amount' => $amount]);


            if ($price > $lastPrice) {
                $str = '↑';
            } else {
                $str = '↓';
            }

            $lastPrice = $price;

            echo sprintf('%d %s', $price, $str), PHP_EOL;

            if($amount <  0){
                $job = new BuyBtc();
                dispatch($job);
            }

            sleep(1);
        }
    }
}
