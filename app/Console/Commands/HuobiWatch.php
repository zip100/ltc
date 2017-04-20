<?php

namespace App\Console\Commands;

use App\Model\Huobi;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;

use Illuminate\Console\Command;

class HuobiWatch extends Command
{
    private $last = [
        1 => 0,
        2 => 0
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
        while (1) {
            $btcPrice = Btc::getInstance()->getLastPrice();
            if ($btcPrice != $this->last[Btc::FLAG]) {

                $this->last[Btc::FLAG] != 0 && Huobi::forceCreate([
                    'type' => Btc::FLAG,
                    'price' => $btcPrice,
                    'amount' => $btcPrice - $this->last[Btc::FLAG],
                ]);

                $this->last[Btc::FLAG] = $btcPrice;
            }


            $ltcPrice = Ltc::getInstance()->getLastPrice();
            if ($ltcPrice != $this->last[Ltc::FLAG]) {

                $this->last[Ltc::FLAG] != 0 && Huobi::forceCreate([
                    'type' => Ltc::FLAG,
                    'price' => $ltcPrice,
                    'amount' => $ltcPrice - $this->last[Ltc::FLAG],
                ]);

                $this->last[Ltc::FLAG] = $ltcPrice;
            }
        }
    }
}
