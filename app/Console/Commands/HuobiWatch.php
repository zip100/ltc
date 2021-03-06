<?php

namespace App\Console\Commands;

use App\Events\NewPrice;
use App\Jobs\Buy;
use App\Model\Huobi;
use App\Module\Huobi\Api;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;

use Illuminate\Console\Command;

class HuobiWatch extends Command
{
    private $last = [
        1 => 0,
        2 => 0
    ];

    private $sendTime = [
        Api::CONIN_BTC => 0,
        Api::CONIN_LTC => 0
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'huobi:watch';

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
        // 系统启动事件
        event(new \App\Events\HuobiWatch(\App\Events\HuobiWatch::ACTION_START));


        \Cache::forget('auto_low');
        \Cache::forget('ltc_price');

        $ltcHigh = 0;

        while (1) {
            $btcRow = $ltcRow = 0;

            /*$btcPrice = Btc::getInstance()->getLastPrice();
            if ($btcPrice != $this->last[Btc::FLAG]) {

                $this->last[Btc::FLAG] != 0 && $btcRow = Huobi::forceCreate([
                    'type' => Btc::FLAG,
                    'price' => $btcPrice,
                    'amount' => $btcPrice - $this->last[Btc::FLAG],
                ]);
                // 最近两分钟幅度
                $btcAmount = Huobi::where('type', Btc::FLAG)->where('created_at', '>', date('Y-m-d H:i:s', time() - 120))->sum('amount');

                if ($btcRow && $btcAmount <= -100 && (time() - $this->sendTime[Api::CONIN_BTC] > 120)) {
                    event(new \App\Events\HuobiPrice($btcRow));

                    $this->sendTime[Api::CONIN_BTC] = time();

                    $btcRow->notice_amount = $btcAmount;
                    $btcRow->save();
                }

                $this->last[Btc::FLAG] = $btcPrice;
                if($btcRow instanceof Huobi){
                    event(new NewPrice($btcRow));
                }
            } */


            $ltcPrice = Ltc::getInstance()->getLastPrice();


            if ($ltcPrice != $this->last[Ltc::FLAG]) {

                if ($ltcPrice > $this->last[Ltc::FLAG]) {
                    $ltcHigh = $ltcPrice;
                    if (\Cache::get('auto_low') == '1' && $ltcPrice - 4 > \Cache::get('ltc_price')) {
                        \Cache::forever('ltc_price', $ltcHigh - 4);
                    }
                }


                $this->last[Ltc::FLAG] != 0 && $ltcRow = Huobi::forceCreate([
                    'type' => Ltc::FLAG,
                    'price' => $ltcPrice,
                    'amount' => $ltcPrice - $this->last[Ltc::FLAG],
                ]);
                // 最近两分钟幅度
                $ltcAmount = Huobi::where('type', Ltc::FLAG)->where('created_at', '>', date('Y-m-d H:i:s', time() - 120))->sum('amount');
                if ($ltcRow && $ltcAmount <= -3 && (time() - $this->sendTime[Api::CONIN_LTC] > 120)) {
                    event(new \App\Events\HuobiPrice($ltcRow));

                    $this->sendTime[Api::CONIN_LTC] = time();


                    $ltcRow->notice_amount = $ltcAmount;
                    $ltcRow->save();
                }


                $this->last[Ltc::FLAG] = $ltcPrice;
                if ($ltcRow instanceof Huobi) {
                    event(new NewPrice($ltcRow));
                }


                $price = \Cache::get('ltc_price');

                if ($price > 0 && $ltcPrice <= $price) {

                    \Cache::forget('auto_low');
                    \Cache::forget('ltc_price');

                    $info = \App\Module\Huobi\Product\Btc::getInstance()->getAccountInfo();
                    Btc::getInstance()->sellMarket($info['available_ltc_display']);
                }

            }

        }
    }
}
